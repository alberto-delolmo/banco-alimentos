<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse {

        $payload = json_decode($request->getContent(), true);

        if(!is_array($payload)){
            return new JsonResponse([
                'error' => 'Formato del JSON inválido',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (empty($payload['name']) || empty($payload['measurementUnit']) || empty($payload['actualStock'])) {
            return new JsonResponse([
                'error' => 'Nombre, Unidad de medida y stock actual son obligatorios',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product -> setName($payload['name']);
        $product -> setDescription($payload['description'] ?? null);
        $product -> setMeasurementUnit($payload['measurementUnit']);
        $product -> setActualStock($payload['actualStock']);

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
