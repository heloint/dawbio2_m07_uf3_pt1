<?php
/* Sub-controller of the MainController
 * corresponding to the "WarehouseController" related operations.
 * @author Dániel Májer
 * */

namespace proven\store\controllers;

require_once "lib/ViewLoader.php";
require_once "lib/Validator.php";

require_once "model/StoreModel.php";
require_once "model/Warehouse.php";

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

class WarehouseController
{
    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var Model
     */
    private $model;

    public function __construct()
    {
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }

    /**
     * Displays warehouse management page.
     * @return void
     */
    public function doWarehouseMng()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                try {
                    //get all users.
                    $result = $this->model->findAllWarehouses();
                } catch (\ErrorException $e) {
                    $this->view->show("message.php", [
                        "message" =>
                            "An error has occured in our server. Please try again later.",
                    ]);
                }

                //pass list to view and show.
                $this->view->show("warehouse/warehousemanage.php", [
                    "list" => $result,
                ]);
            } else {
                $this->view->show("message.php", [
                    "message" => "Don't have permission to visit this page!",
                ]);
            }
        } else {
            $this->view->show("message.php", [
                "message" => "Don't have permission to visit this page!",
            ]);
        }
    }

    /* Searches for a warehouse by it's id
     * and passes an Warehouse object to the view.
     * @return void
     * */
    public function doWarehouseEditForm()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                $data = [];
                //fetch data for selected user
                $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
                if ($id !== false && !is_null($id)) {
                    $warehouse = $this->model->findWarehouseById($id);
                    if (!is_null($warehouse)) {
                        $data["warehouse"] = $warehouse;
                    }
                }
                $this->view->show("warehouse/warehousedetail.php", $data);
            } else {
                $this->view->show("message.php", [
                    "message" => "Don't have permission to visit this page!",
                ]);
            }
        } else {
            $this->view->show("message.php", [
                "message" => "Don't have permission to visit this page!",
            ]);
        }
    }

    /* Modifies the informations of a warehouse in the database.
     * @return void
     * */
    public function doWarehouseModify()
    {
        //get user data from form and validate
        $warehouse = Validator::validateWarehouse(INPUT_POST);
        //add user to database
        if (!is_null($warehouse)) {
            $result = $this->model->modifyWarehouse($warehouse);
            var_dump($result);
            $message =
                $result > 0
                    ? "Successfully modified"
                    : "Error modifying. No modification has been made.";
            $this->view->show("warehouse/warehousedetail.php", [
                "warehouse" => $warehouse,
                "mode" => "edit",
                "message" => $message,
            ]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", [
                "user" => $warehouse,
                "mode" => "edit",
                "message" => $message,
            ]);
        }
    }

    /* Formats the array<Products> and array<WarehouseProduct>
     * data into a more organized
     * assoc. array for the view to display it in a table format.
     * @return array<array<str, str | int>>
     * */
    private function formatTableData(
        array $products,
        array $warehouseStockRegisters
    ): array {
        $tableData = [];
        // First get those products, which have the product in stock.
        foreach ($products as $product) {
            foreach ($warehouseStockRegisters as $stock) {
                if ((int) $stock->getProductId() === (int) $product->getId()) {
                    \array_push($tableData, [
                        "id" => $product->getId(),
                        "code" => $product->getCode(),
                        "description" => $product->getDescription(),
                        "price" => $product->getPrice(),
                        "category_id" => $product->getCategoryId(),
                        "stock" => $stock->getStock(),
                    ]);
                }
            }
        }

        // Sort the found products by their stock value.
        array_multisort(
            array_column($tableData, "stock"),
            SORT_DESC,
            $tableData
        );

        // After that filter out the products,
        // which already included in $tableData.
        $missingProductWarehouses = \array_filter($products, function (
            $product
        ) use ($tableData) {
            if (\array_key_exists($product->getId(), $tableData)) {
                return false;
            }
            return true;
        });

        // Finally fetch the filtered products
        // with stock value as 0.
        foreach ($missingProductWarehouses as $product) {
            \array_push($tableData, [
                "id" => $product->getId(),
                "code" => $product->getCode(),
                "description" => $product->getDescription(),
                "price" => $product->getPrice(),
                "category_id" => $product->getCategoryId(),
                "stock" => 0,
            ]);
        }

        return $tableData;
    }

    /* Retrives the data for a warehouse
     * from WarehouseProductDao and WarehouseDao.
     * @return void
     * */
    public function doWarehouseStockInfo()
    {
        $data = [];
        $data["tableData"] = null;

        //fetch data for selected product
        $id = filter_input(INPUT_POST, "warehouseId", FILTER_VALIDATE_INT);

        if ($id !== false && !is_null($id)) {
            // Get warehouse
            $warehouse = $this->model->findWarehouseById($id);
            if (!is_null($warehouse)) {
                $data["warehouse"] = $warehouse;
            }

            // Get warehouse-product infos.
            $warehouseStockRegisters = $this->model->findStocksByWarehouse(
                $warehouse
            );
            if (!is_null($warehouseStockRegisters)) {
                $data["warehouseStockRegisters"] = $warehouseStockRegisters;
            }

            // Get product infos.
            $products = $this->model->findAllProducts();
            if (!is_null($products)) {
                $data["products"] = $products;
            }

            if (!is_null($products) && !is_null($warehouseStockRegisters)) {
                $data["tableData"] = $this->formatTableData(
                    $products,
                    $warehouseStockRegisters
                );
            }
        }

        $this->view->show("warehouse/warehouseStock.php", $data);
    }
}
