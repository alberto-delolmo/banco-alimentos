<?php

namespace App\App\UI\API\Controller;

use App\App\UI\API\Response\ProductResponse;
use App\Core\Product\Application\ProductService;
use App\Core\Product\Domain\Exception\ProductAlreadyExistsException;
use App\Core\Product\Domain\Exception\ProductNotFoundException;
use App\Core\Product\Domain\Exception\ProductValidationException;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\App\UI\API\Request\Product\CreateProductRequest;
use App\App\UI\API\Request\Product\UpdateProductRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController
{
    #[Route('/api/products', name: 'api_products_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        return new JsonResponse(
            ProductResponse::collection($products)
        );
    }

    #[Route('/api/products/{id}', name: 'api_products_show', methods: ['GET'])]
    public function show(string $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product instanceof Product) {
            return new JsonResponse([
                'message' => sprintf('No existe un producto con el id "%s"', $id),
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            ProductResponse::fromEntity($product)
        );
    }

    #[Route('/api/products', name: 'api_products_create', methods: ['POST'])]
    public function create(Request $request, ProductService $productService): JsonResponse
    {
        $payload = $this->getJsonPayload($request);

        if ($payload === null) {
            return $this->invalidJsonResponse();
        }

        try {
            $product = $productService->create(CreateProductRequest::fromArray($payload));
        } catch (ProductValidationException $exception) {
            return $this->validationErrorResponse($exception);
        } catch (ProductAlreadyExistsException $exception) {
            return $this->conflictResponse($exception);
        }

        return new JsonResponse(
            ProductResponse::fromEntity($product),
            JsonResponse::HTTP_CREATED
        );
    }

    #[Route('/api/products/{id}', name: 'api_products_update', methods: ['PUT'])]
    public function update(
        string $id,
        Request $request,
        ProductService $productService
    ): JsonResponse {
        $payload = $this->getJsonPayload($request);

        if ($payload === null) {
            return $this->invalidJsonResponse();
        }

        try {
            $product = $productService->update($id, UpdateProductRequest::fromArray($payload));
        } catch (ProductValidationException $exception) {
            return $this->validationErrorResponse($exception);
        } catch (ProductNotFoundException $exception) {
            return $this->notFoundResponse($exception);
        }

        return new JsonResponse(
            ProductResponse::fromEntity($product)
        );
    }

    #[Route('/api/products/{id}', name: 'api_products_delete', methods: ['DELETE'])]
    public function delete(string $id, ProductService $productService): JsonResponse
    {
        try {
            $productService->delete($id);
        } catch (ProductNotFoundException $exception) {
            return $this->notFoundResponse($exception);
        }

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function getJsonPayload(Request $request): ?array
    {
        $payload = json_decode($request->getContent(), true);

        return is_array($payload) ? $payload : null;
    }

    private function invalidJsonResponse(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'JSON inválido.',
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    private function validationErrorResponse( ProductValidationException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
            'errors' => $exception->getErrors(),
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    private function conflictResponse(ProductAlreadyExistsException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], JsonResponse::HTTP_CONFLICT);
    }

    private function notFoundResponse(ProductNotFoundException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], JsonResponse::HTTP_NOT_FOUND);
    }
}
