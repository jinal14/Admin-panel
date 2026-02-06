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

      <li class="sidebar-item <?= ($currentPage == 'users.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="users.php">
          <i data-feather="users"></i>
          <span>Users</span>
        </a>
      </li>

      <li class="sidebar-item <?= ($currentPage == 'project.php') ? 'active' : '' ?>">
        <a class="sidebar-link" href="project.php">
          <i data-feather="book"></i>
          <span>Project</span>
        </a>
      </li>
    </ul>
  </div>
</nav>
