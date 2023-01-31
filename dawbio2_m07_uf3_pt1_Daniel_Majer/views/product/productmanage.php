<h2>Product management page</h2>
<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>

<form method="post">
<div class="row g-3 align-items-center">
  <span class="col-auto">
    <label for="search" class="col-form-label">Category to search</label>
  </span>
  <span class="col-auto">
  <input type="text" id="search" name="search" class="form-control" aria-describedby="searchHelpInline" value="<?php echo $params['searchedCategory'] ?? '' ?>">
  </span>
  <span class="col-auto">
    <button class="btn btn-primary" type="submit" name="action" value="product/searchByCategory">Search</button>
  </span>

<?php
    if (isset($_SESSION['userrole'])) {
        if ($_SESSION['userrole'] === 'admin' ||
            $_SESSION['userrole'] === 'staff') {
            echo <<<EOT
                <span class="col-auto">
                  <button class="btn btn-primary" type="submit" name="action" value="product/addForm">Add</button>
                </span>
            EOT;
        }
    }
?>

</div>
</form>
<?php

//display list in a table.
$list = $params['list'] ?? null;
$deletionResult = $params['deletionResult'] ?? null;
$deletedId = $params['deletedId'] ?? null;

if (isset($list)) {

    if (isset($deletionResult) &&
        isset($deletedId)) {

        if ($deletionResult === true) {
            $deletionMessage = 'Product "' . $deletedId . '" has been deleted successfully.';
        } else {
            $deletionMessage = 'Could not delete product"' . $deletedId . '".';

        }
        echo <<<EOT
            <div>
                <p>{$deletionMessage}</p>
            </div>
        EOT;
    }

    echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of products</caption>
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th>Price</th>
    EOT;
    if (isset($_SESSION['userrole'])) {
        if ($_SESSION['userrole'] === 'admin' ||
            $_SESSION['userrole'] === 'staff') {
            echo "<th>Actions</th>";
        }
    }
    echo <<<EOT
        </tr>
        </thead>
        <tbody>
    EOT;
    // $params contains variables passed in from the controller.
    foreach ($list as $elem) {
        echo <<<EOT
            <tr>
                <td>{$elem->getCode()}</td>
                <td>{$elem->getDescription()}</td>
                <td>{$elem->getPrice()}</td>
        EOT;
        if (isset($_SESSION['userrole'])) {
            if ($_SESSION['userrole'] === 'admin' ||
                $_SESSION['userrole'] === 'staff') {
                echo <<<EOT
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="productId" value="{$elem->getId()}">
                            <button class="btn btn-secondary" type="submit" name="action" value="product/stocks">stocks</button>
                            <button class="btn btn-secondary" type="submit" name="action" value="product/editForm">modify</button>
                            <button class="btn btn-secondary" type="submit" name="action" value="product/removeConfirmation">remove</button>
                        </form>
                    </td>
                EOT;
            }
        }
        echo "</tr>";

    }
    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($list), " elements found.";
    echo "</div>";
} else {
    echo "No data found";
}

?>
