<?php
echo <<<EOT
<nav class="navbar navbar-default navbar-expand-sm navbar-light bg-primary">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Store</a>
    </div>
    <div>
    <ul class="nav navbar-nav">
      <li class="active"><a class="nav-link" href="index.php?action=home">Home</a></li>
      <li><a class="nav-link" href="index.php?action=user">Users</a></li>
      <li><a class="nav-link" href="index.php?action=category">Categories</a></li>
      <li><a class="nav-link" href="index.php?action=product">Products</a></li>
      <li><a class="nav-link" href="index.php?action=warehouse">Warehouses</a></li>
    </ul>
    </div>
EOT;

if (!isset($_SESSION['username'])) {
    echo <<<EOT
        <a class="btn btn-info navbar-btn" href="index.php?action=loginform">Login</a>
    EOT;
} else {
    echo <<<EOT
        <a class="btn btn-info navbar-btn" href="index.php?action=logout">Logout</a>
    EOT;
}

echo "</div></nav>";
