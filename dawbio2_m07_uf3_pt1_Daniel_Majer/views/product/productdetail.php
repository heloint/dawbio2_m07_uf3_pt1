<?php
require_once 'lib/Renderer.php';
require_once 'model/Product.php';

use proven\store\model\Product;

echo "<p>Product detail page</p>";
$addDisable = "";
$editDisable = "disabled";

if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "product/{$params['mode']}";
$message = $params['message'] ?? "";

printf("<p>%s</p>", $message);

if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}

$product = $params['product'] ?? new Product();
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderProductFields($product);
echo "<button type='submit' name='action' value='product/add' $addDisable>Add</button>";
echo "<button type='submit' name='action' value='product/modify' $editDisable>Modify</button>";
echo "</form>";
