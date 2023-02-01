<h2>Warehouse management page</h2>
<?php if (isset($params["message"])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params["message"]; ?></strong>
</div>
<?php endif; ?>

<?php
//display list in a table.
$list = $params["list"] ?? null;
$deletionResult = $params["deletionResult"] ?? null;
$deletedId = $params["deletedId"] ?? null;
$searchedCategoryCode = $params["searchedCategoryCode"] ?? null;

if (isset($list)) {
    if (\count($list) < 1) {
        echo '<p class="text-danger mt-4">No data were found.</p>';
    } else {
        echo <<<EOT
    <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
    <caption>List of products</caption>
    <thead class='table-dark'>
    <tr>
        <th>Code</th>
        <th>Address</th>
        <th>Stock</th>
EOT;
        echo <<<EOT
    </tr>
    </thead>
    <tbody>
EOT;
        // $params contains variables passed in from the controller.
        foreach ($list as $elem) {
            echo <<<EOT
    <tr>
        <td><a href="index.php?action=warehouse/edit&id={$elem->getId()}">{$elem->getCode()}</a></td>
        <td>{$elem->getAddress()}</td>
EOT;
            echo <<<EOT
    <td>
        <form action="" method="post">
            <input type="hidden" name="warehouseId" value="{$elem->getId()}">
            <button class="btn btn-secondary" type="submit" name="action" value="warehouse/stocks">stocks</button>
        </form>
    </td>
EOT;
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "<div class='alert alert-info' role='alert'>";
        echo count($list), " elements found.";
        echo "</div>";
    }
}


?>
