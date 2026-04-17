<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="sidebar">
  <div class="sidebar-brand">
    <span class="brand-icon">⬡</span>
    <span class="brand-text">AttendOS</span>
  </div>
  <ul class="nav-links">
    <li>
      <a href="index.php" class="nav-item <?= $current === 'index.php' ? 'active' : '' ?>">
        <span class="nav-icon">⊞</span>
        <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a href="report.php" class="nav-item <?= $current === 'report.php' ? 'active' : '' ?>">
        <span class="nav-icon">◈</span>
        <span>Reports</span>
      </a>
    </li>
  </ul>
  <div class="sidebar-footer">
    <span class="status-dot"></span>
    <span>Live System</span>
  </div>
</nav>