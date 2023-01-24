

<!-- PRODUCT INFO -->
<!-- ================================================== -->
<?php
require_once 'lib/Renderer.php';
require_once 'model/Product.php';

use proven\store\model\Product;

echo "<p>Product details</p>";

$message = $params['message'] ?? "";

$product = $params['product'] ?? new Product();
echo "<div>";
echo proven\lib\views\Renderer::renderProductInfos($product);
echo "</div>";

?>


<!-- STOCK TABLE -->
<!-- ================================================== -->
<?php
// Display warehouses and their informations in a table.
$warehouses = $params['warehouses'] ?? null;
$productStockRegisters = $params['productStockRegisters'] ?? null;

var_dump($warehouses);
echo '<br>';
echo '<br>';
echo '<br>';
var_dump($productStockRegisters);

/* if (isset($warehouses) && isset($productStockRegisters)) {
    echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of users</caption>
        <thead class='table-dark'>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Address</th>
            <th>Stock</th>
        </tr>
        </thead>
        <tbody>
EOT;
    // $params contains variables passed in from the controller.
    foreach ($list as $elem) {
        echo <<<EOT
            <tr>
                <td><a href="index.php?action=user/edit&id={$elem->getId()}">{$elem->getUsername()}</a></td>
                <td>{$elem->getFirstname()} {$elem->getLastname()}</td>
                <td>{$elem->getRole()}</td>
            </tr>               
EOT;
    }
    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($list), " elements found.";
    echo "</div>";   
} else {
    echo "No data found";
} */
?>
