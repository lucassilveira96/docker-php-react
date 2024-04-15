<?php

namespace App\Controllers\Sale;

use App\Helpers\Response;
use App\Services\Sale\SaleService;
use Exception;
use OpenApi\Annotations as OA;

/**
 * Controller responsible for list all sales.
 */
class GetAllSalesController
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
     *     path="/sales",
     *     tags={"Sales"},
     *     summary="Retrieve all sales",
     *     description="Returns a list of all sales, including detailed item and product information.",
     *     @OA\Response(
     *         response=200,
     *         description="List of sales",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Sale")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     * @OA\Schema(
     *     schema="Sale",
     *     type="object",
     *     properties={
     *         @OA\Property(property="id", type="integer", format="int64", example=18),
     *         @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/SaleItem")),
     *         @OA\Property(property="total_amount", type="number", format="float", example=750),
     *         @OA\Property(property="total_tax", type="number", format="float", example=75),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-12T16:53:34Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time"),
     *         @OA\Property(property="deleted_at", type="string", format="date-time")
     *     }
     * )
     * @OA\Schema(
     *     schema="SaleItem",
     *     type="object",
     *     properties={
     *         @OA\Property(property="id", type="integer", format="int64", example=5),
     *         @OA\Property(property="product", ref="#/components/schemas/Product"),
     *         @OA\Property(property="quantity", type="integer", example=2),
     *         @OA\Property(property="price_per_unit", type="number", format="float", example=150),
     *         @OA\Property(property="tax_per_unit", type="number", format="float", example=15),
     *         @OA\Property(property="total_price", type="number", format="float", example=300),
     *         @OA\Property(property="total_tax", type="number", format="float", example=30),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-12T16:53:34Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time"),
     *         @OA\Property(property="deleted_at", type="string", format="date-time")
     *     }
     * )
     */
    public function __invoke()
    {
        header('Content-Type: application/json');

        try {
            $data = $this->saleService->getAllSales();

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
