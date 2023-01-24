<?php
namespace proven\store\controllers;

require_once 'lib/ViewLoader.php';
require_once 'lib/Validator.php';

require_once 'model/StoreModel.php';
require_once 'model/User.php';

use proven\store\model\StoreModel as Model;
use proven\lib\ViewLoader as View;

use proven\lib\views\Validator as Validator;

/* ============== PRODUCT MANAGEMENT CONTROL METHODS ============== */
class ProductController {

    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var Model
     */
    private $model;

    public function __construct() {
        //instantiate the view loader.
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }


    /**
     * displays product management page.
     */
    public function doProductMng() {
        //TODO
        /* $categoryCode = filter_input(INPUT_POST, 'categoryCode'); */
        /* $products = $this->model->findProductsByCategory($categoryCode); */
        $products = $this->model->findAllProducts();
        $this->view->show("product/productmanage.php", ['list' => $products]);
    }

    // PRODUCT METHODS
    // ==================================================
    public function doListProductsByCategory() {
        //get role sent from client to search.
        $categoryToSearch = \filter_input(INPUT_POST, "search");
        if ($categoryToSearch !== false) {
            //get users with that role.
            $foundCategory = $this->model->findCategoryByCode($categoryToSearch);
            $result = $this->model->findProductsByCategory($foundCategory);
            //pass list to view and show.
            $this->view->show("product/productmanage.php", ['list' => $result, 'searchedCategory' => $categoryToSearch]);
        }  else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", ['message' => "No data found"]);
        }
    }
    

    public function doProductForm($mode) {
        $data = array(); 
        $data['mode'] = $mode;
        if ($mode != 'add') {
            //fetch data for selected user
            $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

            if (($id !== false) && (!is_null($id))) {
                $product = $this->model->findProductById($id);
                if (!is_null($product)) {
                    $data['product'] = $product;
                }
             }
        }
        $this->view->show("product/productdetail.php", $data);
    }
    
    public function doProductAdd() {

        //get product data from form and validate
        $product = Validator::validateProduct(INPUT_POST);

        //add product to database
        if (!is_null($product)) {
            $result = $this->model->addProduct($product);
            $message = ($result > 0) ? "Successfully added":"Error adding";
            $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message, 'product' => $product]);
        } else {
            $message = "Invalid data";
            $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message, 'product' => $product]);
        }
    }

    public function doProductModify() {

        //get user data from form and validate
        $product = Validator::validateProduct(INPUT_POST);
        if (!is_null($product)) {
            $result = $this->model->modifyProduct($product);
            $message = ($result > 0) ? "Successfully modified":"Error modifying";
            $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $product]);
        } else {
            $message = "Invalid data";
            $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $product]);
        }
    }

    public function doProductRemovalConfirmation() {
        $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);
        $productToDelete = $this->model->findProductById($id);
        $this->view->show("product/productRemovalConfirmation.php", ['product' => $productToDelete]);
    }

    public function doProductRemove() {
        // TODO: ASK PROFESSOR IF DELETE THE PRODUCTS TOO OR NOT. BECAUSE OF THE CONSTRAINT.
        $affectedRowNum = 0;
        $deletionResult = false;

        $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

        $productToDelete = $this->model->findProductById($id);

        if (!is_null($productToDelete)) {
            $affectedRowNum = $this->model->removeProduct($productToDelete);
        }

        if ($affectedRowNum > 0) {
            $deletionResult = true;
        }

        $allProducts = $this->model->findAllProducts();

        $data = [
            'list' => $allProducts,
            'deletionResult' => $deletionResult,
            'deletedId' => $id
        ];

        $this->view->show("product/productmanage.php", $data);
    }

    private function formatTableData(array $warehouses, array $productStockRegisters): array
    {
        $tableData = array();

        // First get those warehouses, which have the product in stock.
        foreach($warehouses as $warehouse) {
            foreach($productStockRegisters as $stock) {
                if ((int) $stock->getWarehouseId() === (int) $warehouse->getId()) {
                    \array_push($tableData, [
                        'id' => $warehouse->getId(),
                        'code' => $warehouse->getCode(),
                        'address' => $warehouse->getAddress(),
                        'stock'   => $stock->getStock()
                    ]);
                }
            }
        }

        // Sort the found warehouses by their stock value.
        array_multisort(array_column($tableData, 'stock'), SORT_DESC, $tableData);

        // After that filter out the warehouses,
        // which already included in $tableData.
        $missingProductWarehouses = \array_filter($warehouses, function($warehouse) use ($tableData) {
                                        if (\array_key_exists($warehouse->getId(), $tableData)) {
                                            return false;
                                        }
                                        return true;
                                    });

        // Finally fetch the filtered warehouses 
        // with stock value as 0.
        foreach($missingProductWarehouses as $warehouse) {
            \array_push($tableData, [
                'id' => $warehouse->getId(),
                'code' => $warehouse->getCode(),
                'address' => $warehouse->getAddress(),
                'stock'   => 0
            ]);
        }

        return $tableData;
    }

    public function doProductStockInfo() {
        $data = array(); 

        //fetch data for selected product
        $id = filter_input(INPUT_POST, 'productId', FILTER_VALIDATE_INT);

        if (($id !== false) && (!is_null($id))) {
            // Get product
            $product = $this->model->findProductById($id);
            if (!is_null($product)) {
                $data['product'] = $product;
            }

            // Get product-warehouse infos.
            $productStockRegisters = $this->model->getProductStock($product);
            if (!is_null($productStockRegisters )) {
                $data['productStockRegisters'] = $productStockRegisters;
            }

            // Get warehouse infos.
            $warehouses = $this->model->findAllWarehouses();
            if (!is_null($warehouses)) {
                $data['warehouses'] = $warehouses;
            }

            $data['tableData'] = $this->formatTableData($warehouses, $productStockRegisters);
         }

        $this->view->show("product/productStock.php", $data);
    }

    public function doListStockByProduct() {
        $data = array();
        //get role sent from client to search.
        $productToSearch = \filter_input(INPUT_POST, "search");
        if ($productToSearch !== false) {
            //get users with that role.
            $foundProduct = $this->model->findProductByCode($productToSearch);

            if (!is_null($foundProduct)) {
                $data['product'] = $foundProduct;
                // Get product-warehouse infos.
                $productStockRegisters = $this->model->getProductStock($foundProduct);
                if (!is_null($productStockRegisters )) {
                    $data['productStockRegisters'] = $productStockRegisters;
                }

                // Get warehouse infos.
                $warehouses = $this->model->findAllWarehouses();
                if (!is_null($warehouses)) {
                    $data['warehouses'] = $warehouses;
                }

                $data['tableData'] = $this->formatTableData($warehouses, $productStockRegisters);
             }
            //pass list to view and show.
            $this->view->show("product/productStock.php", $data);
        }  else {
            //pass information message to view and show.
            $this->view->show("product/productmanage.php", ['message' => "No data found"]);
        }
    }
}
