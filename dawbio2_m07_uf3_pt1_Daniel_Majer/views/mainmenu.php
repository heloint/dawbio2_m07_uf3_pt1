<?php
echo <<<EOT
<nav class="navbar navbar-default navbar-expand-sm navbar-light bg-primary">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Store</a>
    </div>
    <div>
    <ul class="nav navbar-nav">
      <li class="active"><a class="nav-link" href=""index.php?action=home"">Home</a></li>
      <li><a class="nav-link" href="#">Page 1</a></li>
      <li><a class="nav-link" href="#">Page 2</a></li>
      <li><a class="nav-link" href="#">Page 3</a></li>
    </ul>
    </div>
    <div>
      <a class="nav-link" href="index.php?action=loginform">login</a>
    </div>
  </div>
</nav>
EOT;
