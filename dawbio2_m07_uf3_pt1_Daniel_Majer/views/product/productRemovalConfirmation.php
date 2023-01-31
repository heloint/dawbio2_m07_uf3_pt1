<?php

if (isset($params)) {
    $searchedCategoryCode = $params["searchedCategoryCode"] ?? null;
    echo <<<EOT
<div class="container">
    <form class="row" action="index.php" method="post">
        <div class="row mt-5 d-flex justify-content-center">
            <p class="h4 text-center">Are you sure you want to delete {$params["product"]->getCode()}?</p>
        </div>
        <div class="row d-flex justify-content-center">
                    <input type="hidden" name="productId" value="{$params["product"]->getId()}">
                    <input type="hidden" name="searchedCategoryCode" value="{$searchedCategoryCode}">
                <div class="col-2">
                    <button class="btn btn-primary" name="action" value="product/remove" type="submit" >Confirm</button>
                </div>
                <div class="col-2">
                    <button class="btn btn-secondary" name="action" value="product/cancelRemove" type="submit" >Cancel</button>
                </div>
        </div>
    </form>

</div>
EOT;
} else {
    echo <<<EOT
<div class="container">
    <div class="row d-flex justify-content-center">
        <p class="text-center">Wrong page!</p>
    </div>
</div>
EOT;
}
