<h2>Stock</h2>

<?php if (isset($params["message"])): ?>
    <div class='alert alert-warning'>
    <strong><?php echo $params["message"]; ?></strong>
    </div>
<?php endif; ?>


<!-- PRODUCT INFO -->
<!-- ================================================== -->
<?php
require_once "lib/Renderer.php";
require_once "model/Warehouse.php";

use proven\store\model\Warehouse;

echo "<h4>Warehouse details</h4>";

$message = $params["message"] ?? "";

$warehouse = $params["warehouse"] ?? new Warehouse();
echo "<div>";
echo proven\lib\views\Renderer::renderWarehouseInfos($warehouse);
echo "</div>";
?>

<!-- STOCK TABLE -->
<!-- ================================================== -->
<?php
// Display warehouses and their informations in a table.
$products = $params["products"] ?? null;
$warehouseStockRegisters = $params["warehouseStockRegisters"] ?? null;
$tableData = $params["tableData"] ?? null;

if (isset($products) && isset($warehouseStockRegisters)) {
    echo <<<EOT
        <h4 class="mt-3" >Stock information</h4>
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Stock</th>
        </tr>
        </thead>
        <tbody>
EOT;
    // $params contains variables passed in from the controller.
    foreach ($tableData as $register) {
        echo <<<EOT
            <tr>
                <td>{$register["code"]}</td>
                <td>{$register["stock"]}</td>
            </tr>
EOT;
    }
    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($register), " elements found.";
    echo "</div>";
} else {
    echo '<p class="text-warning display-6">Couldn\'t find this product in any of the warehouses.</p>';
}


?>
