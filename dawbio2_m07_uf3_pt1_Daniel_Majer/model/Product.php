<?php
/* Product class representing a model of the MVC architecture
 * corresponding to the Product related operations.
 * @author Dániel Májer
 * */

namespace proven\store\model;

class Product
{
    private int $id;
    private ?string $code;
    private ?string $description;
    private ?float $price;
    private int $category_id;

    public function __construct(
        int $id = 0,
        string $code = null,
        string $description = null,
        float $price = null,
        int $category_id = 0
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
        $this->price = $price;
        $this->category_id = $category_id;
    }

    /* Get ID of object.
     * @return int
     * */
    public function getId(): int
    {
        return $this->id;
    }

    /* Get code of object.
     * @return string
     * */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /* Get description of object.
     * @return string
     * */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /* Get price of object.
     * @return float | null
     * */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /* Get category ID of object.
     * @return int
     * */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /* Set ID of object.
     * @param id int
     * @return void
     * */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /* Set code of object.
     * @param code string
     * @return void
     * */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /* Set description of object.
     * @param description string
     * @return void
     * */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /* Set price of object.
     * @param price float
     * @return void
     * */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /* Set category ID of object.
     * @param category_id int
     * @return void
     * */
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    /* String representation of the class.
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "User{[id=%d][code=%s][description=%s][price=%.2f€][category_id=%d]}",
            $this->id,
            $this->code,
            $this->description,
            $this->price,
            $this->category_id
        );
    }
}
