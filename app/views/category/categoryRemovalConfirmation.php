<?php
/* Customized confirmation form for a deletion operation.
 * @author Dániel Májer
 * */

if (isset($params)) {
    echo <<<EOT
<div class="container">
    <form class="row" action="index.php" method="post">
        <div class="row mt-5 d-flex justify-content-center">
            <p class="h4 text-center">Are you sure you want to delete {$params["category"]->getCode()}?</p>
        </div>
        <div class="row d-flex justify-content-center">
                    <input type="hidden" name="categoryId" value="{$params["category"]->getId()}">
                <div class="col-2">
                    <button class="btn btn-primary" name="action" value="category/remove" type="submit" >Confirm</button>
                </div>
                <div class="col-2">
                    <button class="btn btn-secondary" name="action" value="category/cancelRemove" type="submit" >Cancel</button>
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
