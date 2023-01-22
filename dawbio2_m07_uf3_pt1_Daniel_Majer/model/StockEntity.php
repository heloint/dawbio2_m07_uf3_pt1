<?php
namespace proven\store\model;

require_once 'model/Product.php';
require_once 'model/Warehouse.php';

use proven\store\model\Product as Product;
use proven\store\model\Warehouse as Warehouse;

class StockEntity {
    private int $warehouse_id;
    private int $product_id;
    private ?int $stock;

    public function __construct(
            int $warehouse_id=0,
            int $product_id=0,
            ?int $stock=null
            ) {
        $this->warehouse_id = $warehouse_id;
        $this->product_id = $product_id;
        $this->stock= $stock;
    }

    public function getWarehouseId(): int{
        return $this->warehouse_id;
    }

    public function getProductId(): int{
        return $this->product_id;
    }

    public function getStock(): ?int {
        return $this->stock;
    }

    public function setWarehouseId(int $warehouse_id): void {
        $this->warehouse_id = $warehouse_id;
    }

    public function setProductId(int $product_id): void {
        $this->product_id = $product_id;
    }

    public function setStock(?int $stock): void {
        $this->stock= $stock;
    }

    public function __toString() {
        return sprintf("StockEntity{[warehouse_id=%d][product_id=%d][stock=%d]}",
                $this->warehouse_id, $this->product_id, $this->stock); }

}

