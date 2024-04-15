<?php

namespace App\Controllers\Product;

use App\Helpers\Response;
use App\Services\Product\ProductService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for update one product.
 */
class UpdateProductController
{
    /**
     * @var ProductService
     */
    private $productService;

    /** 
     * construtor
     */
    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Update an existing product",
     *     description="Updates an existing product with the provided ID, using the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product to be updated.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for updating the product",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="description", type="string", example="cadeira"),
     *             @OA\Property(property="price", type="number", format="float", example=200),
     *             @OA\Property(property="product_type", type="object",
     *                 @OA\Property(property="id", type="integer", format="int64", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        // Fetch data from PUT request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(
                Response::HTTP_BAD_REQUEST,
                ['message' => 'Invalid data provided']
            );

            return;
        }

        try {
            $product = $this->productService->updateProduct($id, $data);

            if ($product) {
                Response::json(
                    Response::HTTP_CREATED,
                    [
                        'message' => 'Product updated successfully',
                        'data' => $product->toArray()
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to updated product']
                );
            }
        } catch (Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error updating product']
            );
        }
    }
}
