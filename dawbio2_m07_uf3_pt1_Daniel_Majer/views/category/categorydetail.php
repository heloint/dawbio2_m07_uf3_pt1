<?php
require_once "lib/Renderer.php";
require_once "model/Category.php";

use proven\store\model\Category;

echo "<h3>Category detail page</h3>";

$message = $params["message"] ?? "";
$result = $params["result"] ?? "";

$messageColor = "text-danger";
if ($result > 0) {
    $messageColor = "text-success";
}

printf(
    "<p style=\"font-size: 2rem;\" class=\"{$messageColor} text-primary\">%s</p>",
    $message
);

$category = $params["category"] ?? new Category();

echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderCategoryFields($category);
echo "<button type='submit' name='action' value='category/modify'>Modify</button>";
echo "</form>";
