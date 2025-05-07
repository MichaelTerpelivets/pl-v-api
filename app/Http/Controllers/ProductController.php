<?php

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(readonly private ProductRepositoryInterface $repo)
    {
    }

    /**
     * Handle the request to filter records based on the provided parameters.
     *
     * @param Request $request Contains the input parameters for filtering, which include 'category', 'min_price', and 'max_price'.
     * @return JsonResponse Returns a JSON response with the filtered data.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->repo->filter($request->only(['category', 'min_price', 'max_price']))
        );
    }

    /**
     * Handles the storage of a new product.
     *
     * @param ProductRequest $request The incoming request containing product data.
     * @return JsonResponse The JSON response with the created product and a 201 status code.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $dto = new ProductDTO(...$request->validated());
        return response()->json($this->repo->create($dto), 201);
    }

    /**
     * Retrieves and returns the details of a specific resource by its ID.
     *
     * @param string $id The unique identifier of the resource.
     * @return JsonResponse The JSON response containing the resource data.
     */
    public function show(string $id): JsonResponse
    {
        try {
            return response()->json($this->repo->find($id));
        } catch (ModelNotFoundException $e) {
            Log::info('Model not found: ' . $e->getMessage());
            return response()->json(['error' => 'Product not found.'], 404);
        }
    }

    /**
     * Updates an existing product with the provided data.
     *
     * @param ProductRequest $request The incoming request containing updated product data.
     * @param string $id The unique identifier of the product to update.
     * @return JsonResponse The JSON response with the updated product data.
     */
    public function update(ProductRequest $request, string $id): JsonResponse
    {
        return response()->json($this->repo->update($id, $request->validated()));
    }

    /**
     * Deletes a resource by its identifier.
     *
     * @param string $id The unique identifier of the resource to be deleted.
     * @return JsonResponse The JSON response indicating successful deletion.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->repo->delete($id);
        return response()->json(['deleted' => true]);
    }
}
