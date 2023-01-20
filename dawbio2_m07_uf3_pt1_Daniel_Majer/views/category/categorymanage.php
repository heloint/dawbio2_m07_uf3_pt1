<h2>Category management page</h2>
<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>

<?php
//display list in a table.
$list = $params['list'] ?? null;
if (isset($list)) {
    echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of categories</caption>
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
EOT;
    // $params contains variables passed in from the controller.
    foreach ($list as $elem) {
        echo <<<EOT
            <tr>
                <td><a href="index.php?action=category/edit&id={$elem->getId()}">{$elem->getCode()}</a></td>
                <td>{$elem->getDescription()}</td>
                <td class="d-flex justify-content-center">
                    <form action="" method="post">
                        <input type="hidden" name="categoryId" value="{$elem->getId()}">
                        <button class="btn btn-secondary" type="submit" name="action" value="category/remove">Delete</button>
                    </form>
                </td>
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
}

?>
