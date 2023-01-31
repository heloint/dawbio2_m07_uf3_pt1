<?php

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

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

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
