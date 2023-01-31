<?php

if (isset($_SESSION['username']) &&
    isset($_SESSION['role'])) {
echo <<<EOT
    <div class="d-flex justify-content-start align-items-center">
        <img src="./images/anonym-user-profile.png" style="height:8rem;width:8rem;">
        <div>
            <p>{$_SESSION['username']}</p> 
            <p>{$_SESSION['role']}</p> 
        </div>
    </div>
EOT;
}

echo <<<EOT
    '<h3 class="text-center">Welcome to Store manager</h3>';

    <div class="d-flex justify-content-center align-items-center">
        <img src="./images/Store-Manager.jpg">
    </div>

    <div class="d-flex justify-content-center align-items-center">
        <p>Where you can manage as much as you want...</p>
    </div>
EOT;
