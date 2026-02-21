<style>
/* Submenu container */
.sidebar-dropdown {
  padding-left: 18px;
  margin-top: 6px;
  border-left: 2px solid rgba(255,255,255,0.08);
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
  background: rgba(255,255,255,0.04);
}

/* Active submenu */
.sidebar-dropdown .sidebar-item.active .sidebar-link {
  color: #ffffff;
  font-weight: 500;
  background: linear-gradient(
    90deg,
    rgba(59,130,246,0.25),
    transparent
  );
}

/* Parent active menu highlight */
.sidebar-item.active > .sidebar-link {
  background: rgba(255,255,255,0.08);
  color: #ffffff;
}
</style>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);

// helper for submenu open
$designerPages = ['designer.php','designer_requests.php','designer_performance.php'];
$clientPages   = ['user.php','feedbacks.php'];
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
      <li class="sidebar-item <?= in_array($currentPage,$designerPages) ? 'active' : '' ?>">
        <a data-bs-target="#designerMenu" data-bs-toggle="collapse" class="sidebar-link">
          <i data-feather="codepen"></i>
          <span>Designer</span>
        </a>

        <ul id="designerMenu"
            class="sidebar-dropdown list-unstyled collapse <?= in_array($currentPage,$designerPages) ? 'show' : '' ?>">
          
          <li class="sidebar-item <?= ($currentPage=='designer.php')?'active':'' ?>">
            <a class="sidebar-link" href="designer.php">Designer List</a>
          </li>

          <li class="sidebar-item <?= ($currentPage=='designer_requests.php')?'active':'' ?>">
            <a class="sidebar-link" href="designer_requests.php">Designer Requests</a>
          </li>

          <li class="sidebar-item <?= ($currentPage=='designer_performance.php')?'active':'' ?>">
            <a class="sidebar-link" href="designer_performance.php">Designer Performance</a>
          </li>
        </ul>
      </li>

      <!-- Clients Menu -->
      <li class="sidebar-item <?= in_array($currentPage,$clientPages) ? 'active' : '' ?>">
        <a data-bs-target="#clientMenu" data-bs-toggle="collapse" class="sidebar-link">
          <i data-feather="users"></i>
          <span>Clients</span>
        </a>

        <ul id="clientMenu"
            class="sidebar-dropdown list-unstyled collapse <?= in_array($currentPage,$clientPages) ? 'show' : '' ?>">
          
          <li class="sidebar-item <?= ($currentPage=='user.php')?'active':'' ?>">
            <a class="sidebar-link" href="user.php">Client List</a>
          </li>

          <li class="sidebar-item <?= ($currentPage=='feedbacks.php')?'active':'' ?>">
            <a class="sidebar-link" href="feedbacks.php">Client Feedback</a>
          </li>
        </ul>
      </li>

      <!-- Project -->
      <li class="sidebar-item <?= ($currentPage=='project.php')?'active':'' ?>">
        <a class="sidebar-link" href="project.php">
          <i data-feather="book"></i>
          <span>Project</span>
        </a>
      </li>

      <!-- Logout -->
      <li class="sidebar-item mt-auto <?= ($currentPage=='logout.php')?'active':'' ?>">
        <a class="sidebar-link" href="logout.php">
          <i data-feather="log-out"></i>
          <span>Logout</span>
        </a>
      </li>

    </ul>
  </div>
</nav>