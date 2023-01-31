<?php
require_once "lib/Renderer.php";
require_once "model/Warehouse.php";
use proven\store\model\Warehouse;

echo "<h3>User detail page</h3>";

$message = $params["message"] ?? "";
printf('<p class="display-6 text-primary">%s</p>', $message);

$warehouse = $params["warehouse"] ?? new Warehouse();
echo "<form method='post' action=\"index.php\">";
echo proven\lib\views\Renderer::renderWarehouseFields($warehouse);
echo "<button type='submit' name='action' value='warehouse/modify'>Modify</button>";
echo "</form>";
