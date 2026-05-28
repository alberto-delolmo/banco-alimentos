<?php

namespace App\Core\Product\Application;

use App\Core\Product\Domain\Exception\ProductAlreadyExistsException;
use App\Core\Product\Domain\Exception\ProductNotFoundException;
use App\Core\Product\Domain\Exception\ProductValidationException;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function create(array $payload): Product
    {
        $product = new Product();
        $product->setId($payload['id'] ?? '');
        $product->setName($payload['name'] ?? '');
        $product->setDescription($payload['description'] ?? null);
        $product->setMeasurementUnit($payload['measurementUnit'] ?? '');
        $product->setActualStock(isset($payload['actualStock']) ? (int) $payload['actualStock'] : null);

        $this->validate($product);

        if ($this->productRepository->find($product->getId()) !== null) {
            throw new ProductAlreadyExistsException($product->getId());
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function update(string $id, array $payload): Product
    {
        $product = $this->findProductOrFail($id);

        $product->setName($payload['name'] ?? '');
        $product->setDescription($payload['description'] ?? null);
        $product->setMeasurementUnit($payload['measurementUnit'] ?? '');
        $product->setActualStock(isset($payload['actualStock']) ? (int) $payload['actualStock'] : null);

        $this->validate($product);

        $this->entityManager->flush();

        return $product;
    }

    public function patch(string $id, array $payload): Product
    {
        $product = $this->findProductOrFail($id);

        if (array_key_exists('name', $payload)) {
            $product->setName((string) $payload['name']);
        }

        if (array_key_exists('description', $payload)) {
            $product->setDescription($payload['description']);
        }

        if (array_key_exists('measurementUnit', $payload)) {
            $product->setMeasurementUnit((string) $payload['measurementUnit']);
        }

        if (array_key_exists('actualStock', $payload)) {
            $product->setActualStock(
                $payload['actualStock'] !== null ? (int) $payload['actualStock'] : null
            );
        }

        $this->validate($product);

        $this->entityManager->flush();

        return $product;
    }

    public function delete(string $id): void
    {
        $product = $this->findProductOrFail($id);

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    private function findProductOrFail(string $id): Product
    {
        $product = $this->productRepository->find($id);

        if (!$product instanceof Product) {
            throw new ProductNotFoundException($id);
        }

        return $product;
    }

    private function validate(Product $product): void
    {
        $errors = $this->validator->validate($product);

        if (count($errors) === 0) {
            return;
        }

        $messages = [];

        foreach ($errors as $error) {
            $messages[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        throw new ProductValidationException($messages);
    }
}
