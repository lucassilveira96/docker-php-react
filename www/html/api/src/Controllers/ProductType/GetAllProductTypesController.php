<?php

namespace App\Controllers\ProductType;

use App\Helpers\Response;
use App\Services\ProductType\ProductTypeService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for list all product types.
 */
class GetAllProductTypesController
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
     *     path="/product-types",
     *     tags={"Product Types"},
     *     summary="Get all product types",
     *     description="Retrieves a list of all product types available in the database.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductType")
     *         )
     *     )
     * )
     */
    public function __invoke()
    {
        header('Content-Type: application/json');

        try {
            $data = $this->productTypeService->getAllProductTypes();

            Response::json(
                Response::HTTP_OK,
                ['data' => $data]
            );
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());

            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['data' => 'Error']
            );
        }
    }
}
