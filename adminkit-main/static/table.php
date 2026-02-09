<div class="row">
						<div class="col-12 col-lg-8 col-xxl-9 d-flex">
							<div class="card flex-fill">
								<div class="card-header">

									<h5 class="card-title mb-0">Latest Projects</h5>
								</div>
								<table class="table table-hover my-0">
									<thead>
										<tr>
											<th>Name</th>
											<th class="d-none d-xl-table-cell">Start Date</th>
											<th class="d-none d-xl-table-cell">End Date</th>
											<th>Status</th>
											<th class="d-none d-md-table-cell">Assignee</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Project Apollo</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Vanessa Tucker</td>
										</tr>
										<tr>
											<td>Project Fireball</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-danger">Cancelled</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
										<tr>
											<td>Project Hades</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Sharon Lessman</td>
										</tr>
										<tr>
											<td>Project Nitro</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-warning">In progress</span></td>
											<td class="d-none d-md-table-cell">Vanessa Tucker</td>
										</tr>
										<tr>
											<td>Project Phoenix</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
										<tr>
											<td>Project X</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Sharon Lessman</td>
										</tr>
										<tr>
											<td>Project Romeo</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Christina Mason</td>
										</tr>
										<tr>
											<td>Project Wombat</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-warning">In progress</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-12 col-lg-4 col-xxl-3 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">

									<h5 class="card-title mb-0">Monthly Sales</h5>
								</div>
								<div class="card-body d-flex w-100">
									<div class="align-self-center chart chart-lg">
										<canvas id="chartjs-dashboard-bar"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>




<?php

// DUMMY USERS (Users + Designers)

// INSERT INTO `user` (id, name, email, username, password_hash, phone, role, created_at) VALUES
// -- LAST WEEK USERS
// ('USR001','Amit','amit1@mail.com','amit1','x','9000000001','user', CURDATE() - INTERVAL 10 DAY),
// ('USR002','Neha','neha1@mail.com','neha1','x','9000000002','user', CURDATE() - INTERVAL 9 DAY),

// -- THIS WEEK USERS
// ('USR003','Rahul','rahul@mail.com','rahul','x','9000000003','user', CURDATE() - INTERVAL 3 DAY),
// ('USR004','Priya','priya@mail.com','priya','x','9000000004','user', CURDATE() - INTERVAL 1 DAY),

// -- DESIGNERS
// ('DES001','Kunal','kunal@mail.com','kunal','x','9000000010','designer', CURDATE() - INTERVAL 8 DAY),
// ('DES002','Riya','riya@mail.com','riya','x','9000000011','designer', CURDATE() - INTERVAL 2 DAY);


// DUMMY DESIGNER PROFILES

// INSERT INTO designer (id, bio, experience_years, rating) VALUES
// ('DES001','Interior designer',5,4.2),
// ('DES002','Modern home designer',3,4.5);


// DUMMY PROJECTS (KEY FOR GROWTH)

// INSERT INTO project (id, user_id, name, description, status, currency, total_estimated_cost, created_at) VALUES
// -- LAST WEEK PROJECTS
// ('PROJ001','USR001','Living Room','Living room design','completed','INR',150000, CURDATE() - INTERVAL 12 DAY),
// ('PROJ002','USR002','Bedroom','Bedroom design','completed','INR',120000, CURDATE() - INTERVAL 9 DAY),

// -- THIS WEEK PROJECTS
// ('PROJ003','USR003','Kitchen','Kitchen redesign','in_progress','INR',200000, CURDATE() - INTERVAL 3 DAY),
// ('PROJ004','USR004','Office','Home office','draft','INR',180000, CURDATE() - INTERVAL 1 DAY);


// DUMMY DESIGNER REQUESTS (Pending Growth)

// INSERT INTO designer_request (id, user_id, designer_id, project_id, status, created_at) VALUES
// -- LAST WEEK PENDING
// ('REQ001','USR001','DES001','PROJ001','pending', CURDATE() - INTERVAL 10 DAY),

// -- THIS WEEK PENDING
// ('REQ002','USR003','DES002','PROJ003','pending', CURDATE() - INTERVAL 3 DAY),
// ('REQ003','USR004','DES001','PROJ004','pending', CURDATE() - INTERVAL 1 DAY);

// INSERT INTO `user` 
// (id, name, email, username, password_hash, phone, role, created_at)
// VALUES
// ('USR008','Rahul Mehta','rahul8@mail.com','rahul8','hash123','900000008','user',NOW()),
// ('USR009','Neha Shah','neha9@mail.com','neha9','hash123','900000009','user',NOW()),
// ('USR010','Amit Patel','amit10@mail.com','amit10','hash123','900000010','user',NOW());

// INSERT INTO `designer`
// (id, bio, experience_years, rating)
// VALUES
// ('DES004','Interior specialist for modern homes',4,4.3),
// ('DES005','Luxury and villa designer',7,4.6);

// INSERT INTO `project`
// (id, user_id, name, description, status, total_estimated_cost, created_at)
// VALUES
// ('PROJ007','USR008','Living Room','Modern living room design','in_progress',75000,NOW()),
// ('PROJ008','USR009','Bedroom','Minimal bedroom setup','draft',45000,NOW()),
// ('PROJ009','USR010','Office','Home office design','draft',60000,NOW());

// INSERT INTO `room`
// (id, project_id, name, length_m, width_m, height_m, budget_estimate, created_at)
// VALUES
// ('ROOM007','PROJ007','Living Room',5.2,4.5,3.0,75000,NOW()),
// ('ROOM008','PROJ008','Bedroom',4.0,3.8,3.0,45000,NOW()),
// ('ROOM009','PROJ009','Office',3.5,3.0,3.0,60000,NOW());

// INSERT INTO `designer_request`
// (id, user_id, designer_id, project_id, status, created_at)
// VALUES
// ('REQ007','USR008','DES004','PROJ007','pending',NOW()),
// ('REQ008','USR009','DES005','PROJ008','pending',NOW()),
// ('REQ009','USR010','DES004','PROJ009','accepted',NOW());

// INSERT INTO `designer_qualification`
// (id, designer_id, qualification_name, institute_name, year_completed)
// VALUES
// ('DQ004','DES004','B.Des Interior','CEPT University',2019),
// ('DQ005','DES005','M.Des Interior','NIFT Delhi',2017);



?>