<?php

namespace App\Controllers\Sale;

use App\Helpers\Response;
use App\Services\Sale\SaleService;
use Exception;
use OpenApi\Annotations as OA;


/**
 * Controller responsible for creating a new sale.
 */
class CreateSaleController
{
    /**
     * @var SaleService
     */
    private $saleService;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->saleService = new SaleService();
    }

    /**
     * @OA\Post(
     *     path="/sales",
     *     tags={"Sales"},
     *     summary="Create a new sale",
     *     description="Creates a new sale with multiple products.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payload for creating a new sale",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"products"},
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 description="List of products and their quantities",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"quantity", "product_id"},
     *                     @OA\Property(property="quantity", type="integer", format="int32", description="The quantity of the product", example=2),
     *                     @OA\Property(property="product_id", type="integer", format="int64", description="The unique identifier of the product", example=4)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sale created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sale created successfully"),
     *             @OA\Property(
     *                 property="sale",
     *                 description="Details of the newly created sale",
     *                 ref="#/components/schemas/Sale"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input, object invalid"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
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
            $product = $this->saleService->createSale($data);

            if ($product) {
                Response::json(
                    Response::HTTP_CREATED,
                    [
                        'message' => 'Sale created successfully'
                    ]
                );
            } else {
                Response::json(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    ['message' => 'Failed to create sale']
                );
            }
        } catch (Exception $e) {
            error_log("Error creating sale: " . $e->getMessage());
            Response::json(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['message' => 'Error creating sale']
            );
        }
    }
}
