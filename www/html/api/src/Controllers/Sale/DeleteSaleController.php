<?php

namespace App\Controllers\Sale;

use App\Helpers\Response;
use App\Services\Sale\SaleService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for delete one sale.
 */
class DeleteSaleController
{
    /**
     * @var SaleService
     */
    private $saleService;

    /** 
     * construtor
     */
    public function __construct()
    {
        $this->saleService = new SaleService();
    }

    /**
     * @OA\Delete(
     *     path="/sales/{id}",
     *     tags={"Sales"},
     *     summary="Delete a sale by its ID",
     *     description="Deletes a sale from the database using its unique ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the sale to be deleted.",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Sale deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        try {
            $sale = $this->saleService->deleteSale($id);


            if ($sale) {
                Response::json(
                    Response::HTTP_OK,
                    [
                        'message' => 'Sale deleted successfully',
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to delete sale']
                );
            }
        } catch (Exception $e) {
            error_log("Error delete sale: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error delete sale']
            );
        }
    }
}
