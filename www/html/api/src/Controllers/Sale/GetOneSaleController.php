<?php

namespace App\Controllers\Sale;

use App\Helpers\Response;
use App\Services\Sale\SaleService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for list one sale.
 */
class GetOneSaleController
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
     * @OA\Get(
     *     path="/sales/{id}",
     *     tags={"Sales"},
     *     summary="Get a sale by ID",
     *     description="Retrieves detailed information about a specific sale by sale ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the sale",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detailed information about the sale",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int64", description="The unique identifier for the sale", example=18),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 description="List of items in the sale",
     *                 @OA\Items(ref="#/components/schemas/SaleItem")
     *             ),
     *             @OA\Property(property="total_amount", type="number", format="float", description="Total amount of the sale", example=750),
     *             @OA\Property(property="total_tax", type="number", format="float", description="Total tax collected for the sale", example=75),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the sale", example="2024-04-12T16:53:34Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the sale"),
     *             @OA\Property(property="deleted_at", type="string", format="date-time", description="Deletion date of the sale, if applicable")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sale not found"
     *     )
     * )
     */
    public function __invoke(int $id)
    {
        header('Content-Type: application/json');

        try {
            $data = $this->saleService->getSaleById($id);
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
