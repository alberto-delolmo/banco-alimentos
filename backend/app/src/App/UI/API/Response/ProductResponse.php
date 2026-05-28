<?php

namespace App\App\UI\API\Response;

use App\Entity\Product;

final class ProductResponse
{
    public static function fromEntity(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'measurementUnit' => $product->getMeasurementUnit(),
            'actualStock' => $product->getActualStock(),
        ];
    }
    public static function collection(array $products): array
    {
        return array_map(
            static fn (Product $product): array => self::fromEntity($product),
            $products
        );
    }
}
