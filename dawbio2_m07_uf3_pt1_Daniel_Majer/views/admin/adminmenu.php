<?php
/* Customized view for the admin role's navigation bar.
 * @author Dániel Májer
 * */

echo <<<EOT
    <nav class="navbar navbar-default navbar-expand-sm navbar-light bg-primary">
EOT;

echo <<<EOT
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

if (
    !isset($_SESSION["username"]) &&
    !isset($_SESSION["userrole"]) &&
    !isset($_SESSION["userFullName"])
) {
    echo <<<EOT
    <a class="btn btn-info navbar-btn" href="index.php?action=loginform">Login</a>
EOT;
} else {
    echo <<<EOT
    <div class="d-flex justify-content-start align-items-center">
        <div>
            {$_SESSION["userFullName"]}
        </div>
        <img src="./images/anonym-user-profile.png" style="height:3rem;width:3rem;">
    </div>
    <a class="btn btn-info navbar-btn" href="index.php?action=logout">Logout</a>
EOT;
}

echo "</div></nav>";
