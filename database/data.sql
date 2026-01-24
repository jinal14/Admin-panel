-- ===============================
-- USER TABLE
-- ===============================
CREATE TABLE User (
    id CHAR(20) PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(15) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(50),
    phone VARCHAR(10),
    profile_pic_url TEXT,
    dob DATE,
    role ENUM('user','designer','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO User (id, name, email, username, password_hash, role) VALUES
('u1','Neel Contractor','neel@mail.com','neel','hash1','user'),
('u2','Kushal Mistry','kushal@mail.com','kushal','hash2','designer'),
('u3','Kinjal Kalal','kinjal@mail.com','kinjal','hash3','designer'),
('u4','Amit Patel','amit@mail.com','amit','hash4','user'),
('u5','Priya Shah','priya@mail.com','priya','hash5','admin');


-- ===============================
-- DESIGNER TABLE
-- ===============================
CREATE TABLE Designer (
    id CHAR(20) PRIMARY KEY,
    bio TEXT,
    experience_years INT,
    portfolio_urls JSON,
    rating DECIMAL(2,1) DEFAULT 0.0,
    CONSTRAINT fk_designer_user
        FOREIGN KEY (id) REFERENCES User(id)
        ON DELETE CASCADE
);

INSERT INTO Designer (id, bio, experience_years, portfolio_urls, rating) VALUES
('u2','Interior designer with modern style',5,
 JSON_ARRAY('https://portfolio1.com','https://portfolio2.com'),4.5),
('u3','Minimalist home designer',3,
 JSON_ARRAY('https://kinjaldesign.com'),4.2);


-- ===============================
-- DESIGNER QUALIFICATION TABLE
-- ===============================
CREATE TABLE Designer_Qualification (
    id CHAR(20) PRIMARY KEY,
    designer_id CHAR(20) NOT NULL,
    qualification_name VARCHAR(50) NOT NULL,
    institute_name VARCHAR(100),
    year_completed YEAR,
    CONSTRAINT fk_qualification_designer
        FOREIGN KEY (designer_id) REFERENCES Designer(id)
        ON DELETE CASCADE
);

INSERT INTO Designer_Qualification 
(id, designer_id, qualification_name, institute_name, year_completed) VALUES
('dq1','u2','Bachelor of Design','NIFT Ahmedabad',2018),
('dq2','u2','Interior Design Certification','Pearl Academy',2020),
('dq3','u3','Master of Design','CEPT University',2019),
('dq4','u3','Advanced AutoCAD','Arena Animation',2021);


-- ===============================
-- PROJECT TABLE
-- ===============================
CREATE TABLE Project (
    id CHAR(20) PRIMARY KEY,
    user_id CHAR(20) NOT NULL,
    name VARCHAR(20) NOT NULL,
    description TEXT,
    status ENUM('draft','in_progress','completed') DEFAULT 'draft',
    currency VARCHAR(3) DEFAULT 'INR',
    total_estimated_cost DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_user
        FOREIGN KEY (user_id) REFERENCES User(id)
        ON DELETE CASCADE
);

INSERT INTO Project (id, user_id, name, description, status, total_estimated_cost) VALUES
('p1','u1','2BHK Interior','Complete interior design','in_progress',450000),
('p2','u4','Office Renovation','Startup office design','draft',800000);


-- ===============================
-- ROOM TABLE
-- ===============================
CREATE TABLE Room (
    id CHAR(20) PRIMARY KEY,
    project_id CHAR(20) NOT NULL,
    name VARCHAR(20) NOT NULL,
    length_m DECIMAL(6,2),
    width_m DECIMAL(6,2),
    height_m DECIMAL(6,2),
    room_3d_model_url TEXT,
    budget_estimate DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_room_project
        FOREIGN KEY (project_id) REFERENCES Project(id)
        ON DELETE CASCADE
);

INSERT INTO Room (id, project_id, name, length_m, width_m, height_m, budget_estimate) VALUES
('r1','p1','Living Room',5.5,4.0,3.0,200000),
('r2','p1','Bedroom',4.5,3.8,3.0,150000),
('r3','p2','Conference Room',6.0,5.0,3.2,300000);


-- ===============================
-- CATALOGUE ITEMS
-- ===============================
CREATE TABLE Catalogue_Items (
    id CHAR(20) PRIMARY KEY,
    name VARCHAR(20),
    category VARCHAR(20),
    dimensions JSON,
    price DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'INR',
    images JSON,
    material VARCHAR(50),
    tags JSON
);

INSERT INTO Catalogue_Items 
(id, name, category, dimensions, price, images, material, tags) VALUES
('c1','Luxury Sofa','sofa',
 JSON_OBJECT('width',200,'height',90,'depth',80),
 55000,
 JSON_ARRAY('img1.jpg','img2.jpg'),
 'Leather',
 JSON_ARRAY('modern','luxury')),
('c2','Wooden Dining Table','table',
 JSON_OBJECT('width',180,'height',75,'depth',90),
 42000,
 JSON_ARRAY('table1.jpg'),
 'Teak Wood',
 JSON_ARRAY('classic','wood'));


-- ===============================
-- AI PROMPTS
-- ===============================
CREATE TABLE Ai_Prompts (
    id CHAR(20) PRIMARY KEY,
    user_id CHAR(20),
    room_id CHAR(20),
    prompt_text TEXT,
    style VARCHAR(50),
    status ENUM('queued','processing','done','failed') DEFAULT 'done',
    result_images JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_prompt_user
        FOREIGN KEY (user_id) REFERENCES User(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_prompt_room
        FOREIGN KEY (room_id) REFERENCES Room(id)
        ON DELETE SET NULL
);

INSERT INTO Ai_Prompts 
(id, user_id, room_id, prompt_text, style, status, result_images) VALUES
('ai1','u1','r1','Modern living room with warm lighting','modern','done',
 JSON_ARRAY('ai1.png','ai2.png')),
('ai2','u4','r3','Minimal office interior','minimal','processing',
 JSON_ARRAY());


-- ===============================
-- DESIGNER REQUEST
-- ===============================
CREATE TABLE Designer_Request (
    id CHAR(20) PRIMARY KEY,
    user_id CHAR(20),
    designer_id CHAR(20),
    project_id CHAR(20),
    status ENUM('pending','live','accepted','declined','solved') DEFAULT 'pending',
    CONSTRAINT fk_request_user
        FOREIGN KEY (user_id) REFERENCES User(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_request_designer
        FOREIGN KEY (designer_id) REFERENCES User(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_request_project
        FOREIGN KEY (project_id) REFERENCES Project(id)
        ON DELETE CASCADE
);

INSERT INTO Designer_Request VALUES
('dr1','u1','u2','p1','accepted'),
('dr2','u4','u3','p2','pending');


-- ===============================
-- FEEDBACK
-- ===============================
CREATE TABLE FeedBack (
    id CHAR(20) PRIMARY KEY,
    from_user_id CHAR(20),
    to_user_id CHAR(20),
    project_id CHAR(20),
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_feedback_from_user
        FOREIGN KEY (from_user_id) REFERENCES User(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_feedback_to_user
        FOREIGN KEY (to_user_id) REFERENCES User(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_feedback_project
        FOREIGN KEY (project_id) REFERENCES Project(id)
        ON DELETE CASCADE
);

INSERT INTO FeedBack VALUES
('f1','u1','u2','p1',5,'Excellent work and communication',NOW()),
('f2','u4','u3','p2',4,'Good design ideas',NOW());
