<?php

namespace App\App\UI\API\Request\Product;

final readonly class CreateProductRequest {

    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public string $measurementUnit,
        public ?int $actualStock,
    ){}

    public static function fromArray(array $payload): self {
        return new self(
            id: isset($payload['id']) ? (string) $payload['id'] : '',
            name: isset($payload['name']) ? (string) $payload['name'] : '',
            description: array_key_exists('description', $payload) ? $payload['description'] : null,
            measurementUnit: isset($payload['measurementUnit']) ? (string) $payload['measurementUnit'] : '',
            actualStock: array_key_exists('actualStock', $payload) && $payload['actualStock'] != null
                ? (int) $payload['actualStock'] : null,
        );
    }
}
