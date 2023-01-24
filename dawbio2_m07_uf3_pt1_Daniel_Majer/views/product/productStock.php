<h2>Stock</h2>

<?php if (isset($params['message'])): ?>
    <div class='alert alert-warning'>
    <strong><?php echo $params['message']; ?></strong>
    </div>
<?php endif ?>

<form method="post">
<div class="row g-3 align-items-center">
  <span class="col-auto">
    <label for="search" class="col-form-label">Product code to search</label>
  </span>
  <span class="col-auto">
  <input type="text" id="search" name="search" class="form-control" aria-describedby="searchHelpInline" value="<?php echo $params['searchedProduct'] ?? '' ?>">
  </span>
  <span class="col-auto">
    <button class="btn btn-primary" type="submit" name="action" value="product/searchByCode">Search</button>
  </span>
</div>
</form>

<!-- PRODUCT INFO -->
<!-- ================================================== -->
<?php
require_once 'lib/Renderer.php';
require_once 'model/Product.php';

use proven\store\model\Product;

echo "<h4>Product details</h4>";

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
$tableData = $params['tableData'] ?? null;

if (isset($warehouses) && isset($productStockRegisters)) {

    echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <h4>Stock information</h4>
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
    foreach ($tableData as $register) {
        echo <<<EOT
            <tr>
                <td> <!-- <a href="index.php?action=user/edit&id={$register['id']}">-->{$register['id']}<!-- </a> --> </td>
                <td>{$register['code']}</td>
                <td>{$register['address']}</td>
                <td>{$register['stock']}</td>
            </tr>               
EOT;
    }
    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($register), " elements found.";
    echo "</div>";   
} else {
    echo "No data found";
}
?>
