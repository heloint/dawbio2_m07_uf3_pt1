<?php

namespace proven\store\model;

class Category {

    private int $id;
    private ?string $code;
    private ?string $description;

    public function __construct(int $id=0, string $code = null, string $description = null) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCode(string $code): void {
        $this->code = $code;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function __toString() {
        return sprintf("User{[id=%d][code=%s][description=%s]}",
                $this->id, $this->code, $this->description);
    }

}
