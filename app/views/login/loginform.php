<?php
/* Customized confirmation form for a login operation.
 * @author Dániel Májer
 * */

echo "<h2>Login page</h2>"; ?>

<?php
/* This file contains the login form
 * @author Dániel Májer
 * */

// Initialize disabling variables.
$disabled = "";
$disabledClass = "";

// If a session has been started,
// and login is correctly validated,
// then disable the login form.
if (isset($_SESSION["username"])) {
    $disabled = "disabled";
}

$invalidUsername = "";
$invalidPassword = "";
$message = "";

// Handling each error types with their of messages.
// ================================================
if (isset($params["invalidUsername"]) && isset($params["invalidPassword"])) {
    $invalidUsername = $params["invalidUsername"];
    $invalidPassword = $params["invalidPassword"];
    $message = "Invalid username or password.";
}
// ================================================

if (!isset($_SESSION["username"]) && !isset($_SESSION["userrole"])) {
    // Print out the login form.
    echo <<<EOT
<div class="container">
    <form class="row d-flex justify-content-center" action="index.php" method="post" >

        <div class="col-3">
            <label for="username">Username</label>
            <div class="w-100"></div>
            <input id="username"
                   name="username"
                   type="text"
                   placeholder="Username .."
                   value="{$invalidUsername}"
                  {$disabled}
            >
        </div>
        <div class="w-100"></div>

        <div class="col-3 my-3">
            <label for="password">Password</label>
            <div class="w-100"></div>
            <input id="password"
                   name="password"
                   type="password"
                   placeholder="Password .."
                   value="{$invalidPassword}"
                  {$disabled}
            >
        </div>
        <div class="w-100"></div>

        <div class="col-3" >
            <button class="btn btn-secondary"
                    id="login"
                    name="action"
                    value="user/login"
                    type="submit" {$disabled} >
                        Login
            </button>
        </div>

    </form>

</div>

<br>
<div class="input-wrapper" style="display:block;text-align:center;">
    <p style="text-align:center;color:red;">{$message}</p>
</div

EOT;
} else {
    echo <<<EOT
    <div class="d-flex justify-content-center align-items-center">
        <h4 class="text-danger">Already logged in!</h4>
    </div>
EOT;
}

