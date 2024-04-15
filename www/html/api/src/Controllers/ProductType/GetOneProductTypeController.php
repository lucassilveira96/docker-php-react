<?php

namespace App\Controllers\ProductType;

use App\Helpers\Response;
use App\Services\ProductType\ProductTypeService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for list one product type.
 */
class GetOneProductTypeController
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
     * @OA\Get(
     *     path="/product-types/{id}",
     *     tags={"Product Types"},
     *     summary="Get a product type by ID",
     *     description="Retrieves a specific product type by its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the product type to be retrieved.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product type not found"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        header('Content-Type: application/json');

        try {
            $data = $this->productTypeService->getProductTypeById($id);
            if ($data) {
                $data = $data->toArray();
            }

            Response::json(
                Response::HTTP_OK,
                ['data' => $data]
            );
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());

            Response::json(
                Response::HTTP_BAD_REQUEST,
                ['data' => 'error']
            );
        }
    }
}
