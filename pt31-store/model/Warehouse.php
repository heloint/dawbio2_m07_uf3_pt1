<?php

namespace proven\store\model;

class Warehouse {

    public function __construct(
            private int $id = 0,
            private ?string $code = null,
            private ?string $address = null
    ) {
        
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setCode(?string $code): void {
        $this->code = $code;
    }

    public function setAddress(?string $address): void {
        $this->address = $address;
    }

    public function __toString() {
        return sprintf("Warehouse{[id=%d][code=%s][address=%s]}",
                $this->id, $this->code, $this->address);
    }

}
