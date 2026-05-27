<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProductController
{
    #[Route('/api/products', name: 'api_products_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $data = array_map(static function ($product): array {
            return [
                'id' => $product-> getId(),
                'name' => $product-> getName(),
                'description' => $product-> getDescripcion(),
                'measurementUnit' => $product-> getMeasurementUnit(),
                'actualStock' => $product-> getActualStock(),
            ];
        }, $products);

        return new JsonResponse($data);
    }

    #[Route('/api/products', name: 'api_products_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager,
        ValidatorInterface $validator, ProductRepository $productRepository
    ): JsonResponse {

        $payload = json_decode($request->getContent(), true);

        if(!is_array($payload)){
            return new JsonResponse([
                'error' => 'Formato del JSON inválido',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product -> setId($payload['id']);
        $product -> setName($payload['name']);
        $product -> setDescription($payload['description'] ?? null);
        $product -> setMeasurementUnit($payload['measurementUnit']);
        $product -> setActualStock(isset($payload['actualStock']) ? (int) $payload['actualStock'] : null);

        $errors = $validator->validate($product);

        if(count($errors) > 0){
            $errorMessages = [];

            foreach ($errors as $error) {
                $errorMessages[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }
            return new JsonResponse([
                'errors' => $errorMessages,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($productRepository->find($product->getId()) !== null) {
            return new JsonResponse([
                'error' => 'Ya existe un producto con este ID',
            ], JsonResponse::HTTP_CONFLICT);
        }

        $entityManager -> persist($product);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'measurementUnit' => $product->getMeasurementUnit(),
            'actualStock' => $product->getActualStock(),
        ], JsonResponse::HTTP_CREATED);
    }
}
