<?php

namespace App\Controllers\ProductType;

use App\Helpers\Response;
use App\Services\ProductType\ProductTypeService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for delete one product type.
 */
class DeleteProductTypeController
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
     * @OA\Delete(
     *     path="/product-types/{id}",
     *     tags={"Product Types"},
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
     *         description="Product Type deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        try {
            $productType = $this->productTypeService->deleteProductType($id);


            if ($productType) {
                Response::json(
                    Response::HTTP_OK,
                    [
                        'message' => 'Product Type deleted successfully',
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to delete product type']
                );
            }
        } catch (Exception $e) {
            error_log("Error delete product: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error delete product type']
            );
        }
    }
}
