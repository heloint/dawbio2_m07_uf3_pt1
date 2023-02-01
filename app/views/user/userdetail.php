<?php
/* Customized view for a User entity's details.
 * @author Dániel Májer
 * */

require_once "lib/Renderer.php";
require_once "model/User.php";
use proven\store\model\User;
echo "<p>User detail page</p>";
$addDisable = "";
$editDisable = "disabled";
if ($params["mode"] != "add") {
    $addDisable = "disabled";
    $editDisable = "";
}
$mode = "user/{$params["mode"]}";
$message = $params["message"] ?? "";
printf('<p class="display-6 text-primary">%s</p>', $message);
if (isset($params["mode"])) {
    printf("<p>mode: %s</p>", $mode);
}
$user = $params["user"] ?? new User();
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderUserFields($user);
echo "<button type='submit' name='action' value='user/add' $addDisable>Add</button>";
echo "<button type='submit' name='action' value='user/modify' $editDisable>Modify</button>";
echo "<button type='submit' name='action' value='user/remove' $editDisable>Remove</button>";
echo "</form>";
