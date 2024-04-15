<?php

namespace App\Controllers\Product;

use App\Helpers\Response;
use App\Services\Product\ProductService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for list all products.
 */
class GetAllProductsController
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
     *     path="/products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns a list of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     )
     * )
     * 
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64", example=4),
     *     @OA\Property(property="name", type="string", example="mouse gammer"),
     *     @OA\Property(property="price", type="number", format="float", example=150),
     *     @OA\Property(property="ean", type="string", example="AB12345678910"),
     *     @OA\Property(property="purchase_price", type="number", format="float", example=75),
     *     @OA\Property(property="sales_margin", type="number", format="float", example=100),
     *     @OA\Property(property="quantity", type="integer", format="int64", example=150),
     *     @OA\Property(property="minimum_quantity", type="integer", format="int64", example=10),
     *     @OA\Property(property="product_type", ref="#/components/schemas/ProductType"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-12T00:02:25Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     *     @OA\Property(property="deleted_at", type="string", format="date-time")
     * )
     * @OA\Schema(
     *     schema="ProductType",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64", example=1),
     *     @OA\Property(property="description", type="string", example="informatica"),
     *     @OA\Property(property="tax", type="number", format="float", example=10),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-11T22:30:11Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time"),
     *     @OA\Property(property="deleted_at", type="string", format="date-time")
     * )
     */
    public function __invoke()
    {
        header('Content-Type: application/json');

        try {
            $data = $this->productService->getAllProducts();

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
