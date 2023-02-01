<?php
/* WarehouseProduct class representing a model of the MVC architecture
 * corresponding to the WarehouseProduct related operations.
 * @author Dániel Májer
 * */

namespace proven\store\model;

require_once "model/Product.php";
require_once "model/Warehouse.php";

use proven\store\model\Product as Product;
use proven\store\model\Warehouse as Warehouse;

class WarehouseProduct
{
    private int $warehouse_id;
    private int $product_id;
    private ?int $stock;

    public function __construct(
        int $warehouse_id = 0,
        int $product_id = 0,
        ?int $stock = null
    ) {
        $this->warehouse_id = $warehouse_id;
        $this->product_id = $product_id;
        $this->stock = $stock;
    }

    /* Get warehouse ID of object.
     * @return int
     * */
    public function getWarehouseId(): int
    {
        return $this->warehouse_id;
    }

    /* Get product ID of object.
     * @return int
     * */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /* Get stock value of object.
     * @return int | null
     * */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /* Set warehouse ID of object.
     * @param warehouse_id int
     * @return void
     * */
    public function setWarehouseId(int $warehouse_id): void
    {
        $this->warehouse_id = $warehouse_id;
    }

    /* Set product ID of object.
     * @param product_id int
     * @return void
     * */
    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }

    /* Set stock value of object.
     * @param stock int | null
     * @return void
     * */
    public function setStock(?int $stock): void
    {
        $this->stock = $stock;
    }

    /* String representation of the class.
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "WarehouseProduct{[warehouse_id=%d][product_id=%d][stock=%d]}",
            $this->warehouse_id,
            $this->product_id,
            $this->stock
        );
    }
}
