<?php
/* Customized view for a Product entity's details.
 * @author Dániel Májer
 * */

require_once "lib/Renderer.php";
require_once "model/Product.php";

use proven\store\model\Product;

echo "<p>Product detail page</p>";
$addDisable = "";
$editDisable = "disabled";

if ($params["mode"] != "add") {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "product/{$params["mode"]}";
$message = $params["message"] ?? "";

$messageColor = "";
if (isset($params["result"])) {
    if ($params["result"] > 0) {
        $messageColor = "text-success";
    } elseif ($params["result"] <= 0) {
        $messageColor = "text-danger";
    }
}

printf('<p class="display-6 %s">%s</p>', $messageColor, $message);

if (isset($params["mode"])) {
    printf("<p>mode: %s</p>", $mode);
}

$product = $params["product"] ?? new Product();
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderProductFields($product);
echo "<button type='submit' name='action' value='product/add' $addDisable>Add</button>";
echo "<button type='submit' name='action' value='product/modify' $editDisable>Modify</button>";
echo "</form>";
