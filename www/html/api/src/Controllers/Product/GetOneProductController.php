<?php

namespace App\Controllers\Product;

use App\Helpers\Response;
use App\Services\Product\ProductService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for list one product.
 */
class GetOneProductController
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
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     summary="Get a product by its ID",
     *     description="Retrieves a product using its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        header('Content-Type: application/json');

        try {
            $data = $this->productService->getProductById($id);
            if ($data) {
                $data = $data->toArray();
            }

            Response::json(Response::HTTP_OK, ['data' => $data]);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            Response::json(
                Response::HTTP_BAD_REQUEST,
                ['data' => 'Error']
            );
        }
    }
}
