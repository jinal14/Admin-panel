<?php
// Detect current page
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav id="sidebar" class="sidebar js-sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="index.php">
      <span class="align-middle">VibeUp</span>
    </a>

    <ul class="sidebar-nav">
      <li class="sidebar-header">Pages</li>

      <li class="sidebar-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="index.php">
          <i data-feather="sliders"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="sidebar-item <?= ($currentPage == 'designer.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="designer.php">
          <i data-feather="codepen"></i>
          <span>Designer</span>
        </a>
      </li>
      

      <li class="sidebar-item <?= ($currentPage == 'user.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="user.php">
          <i data-feather="users"></i>
          <span>Clients</span>
        </a>
      </li>

      <li class="sidebar-item <?= ($currentPage == 'project.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="project.php">
          <i data-feather="book"></i>
          <span>Project</span>
        </a>
      </li>
      <li class="sidebar-item <?= ($currentPage == 'feedbacks.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="feedbacks.php">
          <i data-feather="message-square"></i>
          <span>Feedbacks</span>
        </a>
      </li>

      <li class="sidebar-item <?= ($currentPage == 'designer_requests.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="designer_requests.php">
          <i data-feather="database"></i>
          <span>Designer Requests</span>
        </a>
      </li>
      <li class="sidebar-item <?= ($currentPage == 'designer_performance.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="designer_performance.php">
          <i data-feather="bar-chart-2"></i>
          <span>Designer Performance</span>
        </a>
      </li>
      
    </ul>
  </div>
</nav>
