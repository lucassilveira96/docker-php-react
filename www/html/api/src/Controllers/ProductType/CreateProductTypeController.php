<?php

namespace App\Controllers\ProductType;

use App\Helpers\Response;
use App\Services\ProductType\ProductTypeService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for create one product type.
 */
class CreateProductTypeController
{
    /**
     * @var ProductTypeService
     */
    private $productTypeService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->productTypeService = new ProductTypeService();
    }

    /**
     * @OA\Post(
     *     path="/product-types",
     *     tags={"Product Types"},
     *     summary="Create a new product type",
     *     description="Adds a new product type to the database with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for creating a new product type",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"description", "tax"},
     *             @OA\Property(property="description", type="string", example="Electronics"),
     *             @OA\Property(property="tax", type="number", format="float", example=15)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product type created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function __invoke()
    {
        header('Content-Type: application/json');

        // Fetch data from POST request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            Response::json(Response::HTTP_BAD_REQUEST, ['message' => 'Invalid data provided']);

            return;
        }

        try {
            $productType = $this->productTypeService->createProductType($data);

            if ($productType) {
                Response::json(
                    Response::HTTP_CREATED,
                    [
                        'message' => 'Product Type created successfully',
                        'data' => $productType->toArray()
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to create product type']
                );
            }
        } catch (Exception $e) {
            error_log("Error creating product type: " . $e->getMessage());

            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error creating product type']
            );
        }
    }
}
