<?php
/* Warehouse class representing a model of the MVC architecture
 * corresponding to the Warehouse related operations.
 * @author Dániel Májer
 * */

namespace proven\store\model;

class Warehouse
{
    public function __construct(
        private int $id = 0,
        private ?string $code = null,
        private ?string $address = null
    ) {
    }

    /* Get ID of object.
     * @return int
     * */
    public function getId(): int
    {
        return $this->id;
    }

    /* Get code of object.
     * @return string | null
     * */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /* Get address of object.
     * @return string | null
     * */
    public function getAddress(): ?string
    {
        return $this->address;
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
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /* Set code of object.
     * @param address string
     * @return void
     * */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    /* String representation of the class.
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "Warehouse{[id=%d][code=%s][address=%s]}",
            $this->id,
            $this->code,
            $this->address
        );
    }
}
