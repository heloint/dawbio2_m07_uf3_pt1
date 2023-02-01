<?php
/* Category class representing a model of the MVC architecture
 * corresponding to the Category related operations.
 * @author Dániel Májer
 * */

namespace proven\store\model;

class Category
{
    private int $id;
    private ?string $code;
    private ?string $description;

    public function __construct(
        int $id = 0,
        string $code = null,
        string $description = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
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

    /* Get description of object.
     * @return string | null
     * */
    public function getDescription(): ?string
    {
        return $this->description;
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

    /* String representation of the class.
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            "User{[id=%d][code=%s][description=%s]}",
            $this->id,
            $this->code,
            $this->description
        );
    }
}
