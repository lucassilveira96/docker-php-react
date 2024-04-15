<?php

namespace App\Controllers\Product;

use App\Helpers\Response;
use App\Services\Product\ProductService;
use Exception;
use OpenApi\Annotations as OA;


/**
 * Controller responsible for creating a new product.
 *
 * @OA\Info(
 *     title="API PHP",
 *     version="1.0.0",
 *     description="API of poducts, product_types and sales",
 *     @OA\Contact(
 *         email="lucas.silva.silveira@rede.ulbra.br"
 *     )
 * )
 */
class CreateProductController
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Controller responsible for create one product.
     * 
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     description="Adds a new product to the database with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"description", "price", "product_type"},
     *             @OA\Property(property="description", type="string", example="mouse"),
     *             @OA\Property(property="price", type="number", format="float", example=150),
     *             @OA\Property(property="ean", type="string", format="string", example="AB12345678910"),
     *             @OA\Property(property="purchase_price", type="number", format="float", example=75),
     *             @OA\Property(property="sales_margin", type="number", format="float", example=100),
     *             @OA\Property(property="quantity", type="integer", format="int64", example=150),
     *             @OA\Property(property="minimum_quantity", type="integer", format="int64", example=10),
     *             @OA\Property(property="product_type", type="object",
     *                 required={"id"},
     *                 @OA\Property(property="id", type="integer", format="int64", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function __invoke()
    {
        // Fetch data from POST request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(
                Response::HTTP_BAD_REQUEST,
                ['message' => 'Invalid data provided']
            );

            return;
        }

        try {
            $product = $this->productService->createProduct($data);

            if ($product) {
                Response::json(
                    Response::HTTP_CREATED,
                    [
                        'message' => 'Product created successfully',
                        'data' => $product->toArray()
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to create product']
                );
            }
        } catch (Exception $e) {
            error_log("Error creating product: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error creating product']
            );
        }
    }
}
