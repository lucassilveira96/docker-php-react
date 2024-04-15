<?php

namespace App\Controllers\Product;

use App\Helpers\Response;
use App\Services\Product\ProductService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for delete one product.
 */
class DeleteProductController
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
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product by its ID",
     *     description="Deletes a product from the database using its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product to be deleted.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        try {
            $product = $this->productService->deleteProduct($id);


            if ($product) {
                Response::json(
                    Response::HTTP_OK,
                    [
                        'message' => 'Product deleted successfully',
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to delete product']
                );
            }
        } catch (Exception $e) {
            error_log("Error delete product: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error delete product']
            );
        }
    }
}
