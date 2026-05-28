<?php

namespace App\Core\Product\Domain\Exception;

final class ProductValidationException extends \RuntimeException {

    public function __construct(private readonly array $errors) {
        parent::__construct(sprintf('Los datos del producto no son válidos.'));
    }

    public function getErrors(): array{
        return $this->errors;
    }
}
