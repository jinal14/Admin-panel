<style>
/* Submenu container */
.sidebar-dropdown {
    padding-left: 18px;
    margin-top: 6px;
    border-left: 2px solid rgba(255, 255, 255, 0.08);
}

/* Submenu item */
.sidebar-dropdown .sidebar-item .sidebar-link {
    font-size: 13px;
    color: #9aa7b2;
    padding: 6px 15px 6px 22px;
    position: relative;
}

/* Dot indicator before submenu */
.sidebar-dropdown .sidebar-link::before {
    content: "•";
    position: absolute;
    left: 8px;
    font-size: 16px;
    color: #6c757d;
}

/* Hover effect */
.sidebar-dropdown .sidebar-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.04);
}

/* Active submenu */
.sidebar-dropdown .sidebar-item.active .sidebar-link {
    color: #ffffff;
    font-weight: 500;
    background: linear-gradient(90deg,
            rgba(59, 130, 246, 0.25),
            transparent);
}

/* Parent active menu highlight */
.sidebar-item.active>.sidebar-link {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
}

/* Parent menu with submenu */
.sidebar-link.has-submenu {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Icon + text wrapper */
.sidebar-link .menu-left {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Caret */
.submenu-caret::before {
  content: "▾";
  font-size: 20px;
  color: #9aa7b2;
  transition: transform 0.25s ease;
}

/* Rotate caret when active */
.sidebar-item.active > .sidebar-link .submenu-caret::before {
  transform: rotate(180deg);
  color: #ffffff;
}

/* ================================
   Smooth Submenu Animation
================================ */

/* Override bootstrap collapse behavior */
.sidebar-dropdown.collapse {
    display: block;                 /* keep visible for animation */
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-6px);
    transition:
        max-height 0.45s ease,
        opacity 0.35s ease,
        transform 0.35s ease;
}

/* When submenu is open */
.sidebar-dropdown.collapse.show {
    max-height: 500px;              /* large enough for submenu */
    opacity: 1;
    transform: translateY(0);
}

/* Smooth hover animation for submenu items */
.sidebar-dropdown .sidebar-link {
    transition:
        background 0.25s ease,
        color 0.25s ease,
        padding-left 0.25s ease;
}

.sidebar-dropdown .sidebar-link:hover {
    padding-left: 26px;
}

/* Smooth parent hover */
.sidebar-item > .sidebar-link {
    transition: background 0.25s ease, color 0.25s ease;
}
.submenu-caret::before {
    content: "▾";
    font-size: 18px;
    color: #9aa7b2;
    display: inline-block;
    transition:
        transform 0.35s cubic-bezier(0.4, 0, 0.2, 1),
        color 0.25s ease;
}

.sidebar-item.active > .sidebar-link .submenu-caret::before {
    transform: rotate(180deg);
    color: #ffffff;
}
.sidebar-dropdown li {
    opacity: 0;
    transform: translateX(-6px);
    transition: all 0.3s ease;
}

.sidebar-dropdown.show li {
    opacity: 1;
    transform: translateX(0);
}

.sidebar-dropdown.show li:nth-child(1) { transition-delay: 0.05s; }
.sidebar-dropdown.show li:nth-child(2) { transition-delay: 0.10s; }
.sidebar-dropdown.show li:nth-child(3) { transition-delay: 0.15s; }

/* ================================
   Equal gap between MAIN pages only
================================ */

/* Space between top-level sidebar items */
.sidebar-nav > .sidebar-item {
    margin-bottom: 10px;   /* adjust: 8px | 10px | 12px */
}

/* Prevent submenu items from inheriting margin */
.sidebar-dropdown .sidebar-item {
    margin-bottom: 0;
}
.sidebar-nav > .sidebar-item > .sidebar-link {
    padding-top: 12px;
    padding-bottom: 12px;
    font-size: 14.5px;
}
</style>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);

// helper for submenu open
$designerPages = ['designer.php', 'designer_requests.php', 'designer_performance.php'];
$clientPages = ['user.php', 'feedbacks.php'];
?>

<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">

        <a class="sidebar-brand" href="index.php">
            <span class="align-middle">VibeUp</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">Pages</li>

            <!-- Dashboard -->
            <li class="sidebar-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
                <a class="sidebar-link" href="index.php">
                    <i data-feather="sliders"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Designer Menu -->
            <li class="sidebar-item <?= in_array($currentPage, $designerPages) ? 'active' : '' ?>">
                <a data-bs-target="#designerMenu" data-bs-toggle="collapse" class="sidebar-link has-submenu">

                    <span class="menu-left">
                        <i data-feather="codepen"></i>
                        <span>Designer</span>
                    </span>

                    <span class="submenu-caret"></span>
                </a>

                <ul id="designerMenu"
                    class="sidebar-dropdown list-unstyled collapse <?= in_array($currentPage, $designerPages) ? 'show' : '' ?>">

                    <li class="sidebar-item <?= ($currentPage == 'designer.php') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="designer.php">Designer List</a>
                    </li>

                    <li class="sidebar-item <?= ($currentPage == 'designer_requests.php') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="designer_requests.php">Designer Requests</a>
                    </li>

                    <li class="sidebar-item <?= ($currentPage == 'designer_performance.php') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="designer_performance.php">Designer Performance</a>
                    </li>
                </ul>
            </li>

            <!-- Clients Menu -->
            <li class="sidebar-item <?= in_array($currentPage, $clientPages) ? 'active' : '' ?>">
                <a data-bs-target="#clientMenu" data-bs-toggle="collapse" class="sidebar-link has-submenu">

                    <span class="menu-left">
                        <i data-feather="users"></i>
                        <span>Clients</span>
                    </span>

                    <span class="submenu-caret"></span>
                </a>
                <ul id="clientMenu"
                    class="sidebar-dropdown list-unstyled collapse <?= in_array($currentPage, $clientPages) ? 'show' : '' ?>">

                    <li class="sidebar-item <?= ($currentPage == 'user.php') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="user.php">Client List</a>
                    </li>

                    <li class="sidebar-item <?= ($currentPage == 'feedbacks.php') ? 'active' : '' ?>">
                        <a class="sidebar-link" href="feedbacks.php">Client Feedback</a>
                    </li>
                </ul>
            </li>

            <!-- Project -->
            <li class="sidebar-item <?= ($currentPage == 'project.php') ? 'active' : '' ?>">
                <a class="sidebar-link" href="project.php">
                    <i data-feather="book"></i>
                    <span>Project</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($currentPage == 'report.php') ? 'active' : '' ?>">
                <a class="sidebar-link" href="report.php">
                    <i data-feather="bar-chart-2"></i>
                    <span>Report</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="sidebar-item mt-auto <?= ($currentPage == 'logout.php') ? 'active' : '' ?>">
                <a class="sidebar-link" href="logout.php">
                    <i data-feather="log-out"></i>
                    <span>Logout</span>
                </a>
            </li>

        </ul>
    </div>
</nav>