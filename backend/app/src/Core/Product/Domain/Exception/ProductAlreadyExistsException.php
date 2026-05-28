<?php

namespace App\Core\Product\Domain\Exception;

final class ProductAlreadyExistsException extends \RuntimeException {

    public function __construct(string $id) {
        parent::__construct(sprintf('El producto con el id "%s" ya existe', $id));
    }
}
