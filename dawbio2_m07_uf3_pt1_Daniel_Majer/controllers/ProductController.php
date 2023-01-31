<?php
namespace proven\store\controllers;

require_once "lib/ViewLoader.php";
require_once "lib/Validator.php";

require_once "model/StoreModel.php";
require_once "model/User.php";

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

class ProductController
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
     * Displays product management page.
     * @return void
     */
    public function doProductMng()
    {
        $this->view->show("product/productmanage.php", []);
    }

    /* Search all products by the given category
     * and pass them to the view as an array<Category>.
     * @return void
     * */
    public function doListProductsByCategory()
    {
        //get role sent from client to search.
        $categoryToSearchCode = \filter_input(INPUT_POST, "search");

        if ($categoryToSearchCode !== false) {
            $result = [];
            //get users with that role.
            $foundCategory = $this->model->findCategoryByCode(
                $categoryToSearchCode
            );
            if ($foundCategory) {
                $result = $this->model->findProductsByCategory($foundCategory);
            }
            //pass list to view and show.
            $this->view->show("product/productmanage.php", [
                "list" => $result,
                "searchedCategoryCode" => $categoryToSearchCode,
            ]);
        } else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", [
                "message" => "No data found",
            ]);
        }
    }

    /* Redirect the user to the view of
     * a form with the products details in it.
     * @return void
     * */
    public function doProductForm($mode)
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                $data = [];
                $data["mode"] = $mode;
                if ($mode != "add") {
                    //fetch data for selected user
                    $id = filter_input(
                        INPUT_POST,
                        "productId",
                        FILTER_VALIDATE_INT
                    );

                    if ($id !== false && !is_null($id)) {
                        $product = $this->model->findProductById($id);
                        if (!is_null($product)) {
                            $data["product"] = $product;
                        }
                    }
                }
                $this->view->show("product/productdetail.php", $data);
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

    /* Adds a product to the database.
     * @return void
     * */
    public function doProductAdd()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                //get product data from form and validate
                $product = Validator::validateProduct(INPUT_POST);

                //add product to database
                if (!is_null($product)) {
                    $result = $this->model->addProduct($product);
                    $message =
                        $result > 0
                            ? "Successfully added new product."
                            : "Failed adding new product.";
                    $this->view->show("product/productdetail.php", [
                        "mode" => "add",
                        "message" => $message,
                        "result" => $result,
                        "product" => $product,
                    ]);
                } else {
                    $message = "Invalid data";
                    $this->view->show("product/productdetail.php", [
                        "mode" => "add",
                        "message" => $message,
                        "result" => -1,
                        "product" => $product,
                    ]);
                }
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

    /* Modifies the informations of a product in the database.
     * @return void
     * */
    public function doProductModify()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                //get user data from form and validate
                $product = Validator::validateProduct(INPUT_POST);
                if (!is_null($product)) {
                    $result = $this->model->modifyProduct($product);
                    $message =
                        $result > 0
                            ? "Successfully modified"
                            : "Error modifying";
                    $this->view->show("product/productdetail.php", [
                        "mode" => "edit",
                        "message" => $message,
                        "product" => $product,
                    ]);
                } else {
                    $message = "Invalid data";
                    $this->view->show("product/productdetail.php", [
                        "mode" => "edit",
                        "message" => $message,
                        "product" => $product,
                    ]);
                }
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

    /* Redirects the user to the view of deletion
     * confirmation of a product.
     * @return void
     * */
    public function doProductRemovalConfirmation()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                $id = filter_input(
                    INPUT_POST,
                    "productId",
                    FILTER_VALIDATE_INT
                );
                $searchedCategoryCode = filter_input(
                    INPUT_POST,
                    "searchedCategoryCode"
                );

                $productToDelete = $this->model->findProductById($id);
                $this->view->show("product/productRemovalConfirmation.php", [
                    "product" => $productToDelete,
                    "searchedCategoryCode" => $searchedCategoryCode,
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

    /* Removes a product from the database.
     * @return void
     * */
    public function doProductRemove()
    {
        if (isset($_SESSION["userrole"])) {
            if (
                $_SESSION["userrole"] === "admin" ||
                $_SESSION["userrole"] === "staff"
            ) {
                $affectedRowNum = 0;
                $deletionResult = false;
                $products = null;

                $id = filter_input(
                    INPUT_POST,
                    "productId",
                    FILTER_VALIDATE_INT
                );
                $searchedCategoryCode = filter_input(
                    INPUT_POST,
                    "searchedCategoryCode"
                );

                // Remove the product if it's found by id.
                $productToDelete = $this->model->findProductById($id);
                if (!is_null($productToDelete)) {
                    $affectedRowNum = $this->model->removeProduct(
                        $productToDelete
                    );
                }

                // Check if the deletion was successful,
                // and get a boolean flag.
                if ($affectedRowNum > 0) {
                    $deletionResult = true;
                }

                // Get products associated with the same
                // category code with which they were searched.
                $foundCategory = $this->model->findCategoryByCode(
                    $searchedCategoryCode
                );
                if ($foundCategory) {
                    $products = $this->model->findProductsByCategory(
                        $foundCategory
                    );
                }

                // Pass the result datas to view.
                $data = [
                    "list" => $products,
                    "deletionResult" => $deletionResult,
                    "deletedId" => $id,
                ];

                $this->view->show("product/productmanage.php", $data);
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

    /* Formats the array<Warehouse> and array<WarehouseProduct>
     * data into a more organized
     * assoc. array for the view to display it in a table format.
     * @return array<array<str, str | int>>
     * */
    private function formatTableData(
        array $warehouses,
        array $productStockRegisters
    ): array {
        $tableData = [];

        // First get those warehouses, which have the product in stock.
        foreach ($warehouses as $warehouse) {
            foreach ($productStockRegisters as $stock) {
                if (
                    (int) $stock->getWarehouseId() === (int) $warehouse->getId()
                ) {
                    \array_push($tableData, [
                        "id" => $warehouse->getId(),
                        "code" => $warehouse->getCode(),
                        "address" => $warehouse->getAddress(),
                        "stock" => $stock->getStock(),
                    ]);
                }
            }
        }

        // Sort the found warehouses by their stock value.
        array_multisort(
            array_column($tableData, "stock"),
            SORT_DESC,
            $tableData
        );

        // After that filter out the warehouses,
        // which already included in $tableData.
        $missingProductWarehouses = \array_filter($warehouses, function (
            $warehouse
        ) use ($tableData) {
            if (\array_key_exists($warehouse->getId(), $tableData)) {
                return false;
            }
            return true;
        });

        // Finally fetch the filtered warehouses
        // with stock value as 0.
        foreach ($missingProductWarehouses as $warehouse) {
            \array_push($tableData, [
                "id" => $warehouse->getId(),
                "code" => $warehouse->getCode(),
                "address" => $warehouse->getAddress(),
                "stock" => 0,
            ]);
        }
        return $tableData;
    }

    /* Retrives the data for a product
     * from WarehouseProductDao and WarehouseDao.
     * @return void
     * */
    public function doProductStockInfo()
    {
        $data = [];
        $data["tableData"] = null;

        //fetch data for selected product
        $id = filter_input(INPUT_POST, "productId", FILTER_VALIDATE_INT);

        if ($id !== false && !is_null($id)) {
            // Get product
            $product = $this->model->findProductById($id);
            if (!is_null($product)) {
                $data["product"] = $product;
            }

            // Get product-warehouse infos.
            $productStockRegisters = $this->model->findStocksByProduct(
                $product
            );
            if (!is_null($productStockRegisters)) {
                $data["productStockRegisters"] = $productStockRegisters;
            }

            // Get warehouse infos.
            $warehouses = $this->model->findAllWarehouses();
            if (!is_null($warehouses)) {
                $data["warehouses"] = $warehouses;
            }

            if (!is_null($warehouses) && !is_null($productStockRegisters)) {
                $data["tableData"] = $this->formatTableData(
                    $warehouses,
                    $productStockRegisters
                );
            }
        }

        $this->view->show("product/productStock.php", $data);
    }

    /* Gets an input (product code) from a search form,
     * gets the warehouse and stock data by it, then returns it to the view.
     * @return void
     * */
    public function doListStockByProduct()
    {
        $data = [];
        //get role sent from client to search.
        $productToSearch = \filter_input(INPUT_POST, "search");
        if ($productToSearch !== false) {
            //get users with that role.
            $foundProduct = $this->model->findProductByCode($productToSearch);

            if (!is_null($foundProduct)) {
                $data["product"] = $foundProduct;
                // Get product-warehouse infos.
                $productStockRegisters = $this->model->findStocksByProduct(
                    $foundProduct
                );
                if (!is_null($productStockRegisters)) {
                    $data["productStockRegisters"] = $productStockRegisters;
                }

                // Get warehouse infos.
                $warehouses = $this->model->findAllWarehouses();
                if (!is_null($warehouses)) {
                    $data["warehouses"] = $warehouses;
                }

                $data["tableData"] = $this->formatTableData(
                    $warehouses,
                    $productStockRegisters
                );
            }
            //pass list to view and show.
            $this->view->show("product/productStock.php", $data);
        } else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", [
                "message" => "No data found",
            ]);
        }
    }
}
