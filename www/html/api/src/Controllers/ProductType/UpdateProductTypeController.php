<?php

namespace App\Controllers\ProductType;

use App\Helpers\Response;
use App\Services\ProductType\ProductTypeService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for update one product type.
 */
class UpdateProductTypeController
{
    /**
     * @var ProductTypeService
     */
    private $productTypeService;

    /** 
     * construtor
     */
    public function __construct()
    {
        $this->productTypeService = new ProductTypeService();
    }

    /**
     * @OA\Put(
     *     path="/product-types/{id}",
     *     tags={"Product Types"},
     *     summary="Update an existing product type",
     *     description="Updates an existing product type with the provided ID, using the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product type to be updated.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="JSON payload for updating the product type",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="description", type="string", example="Electronics"),
     *             @OA\Property(property="tax", type="number", format="float", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product type updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product type not found"
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
            $productType = $this->productTypeService->updateProductType($id, $data);

            if ($productType) {
                Response::json(
                    Response::HTTP_CREATED,
                    [
                        'message' => 'Product type updated successfully',
                        'data' => $productType->toArray()
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to updated product type']
                );
            }
        } catch (Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error updating product type']
            );
        }
    }
}
