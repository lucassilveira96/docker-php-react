<?php

namespace App\Routes;

use App\Controllers\Product\{
    CreateProductController,
    DeleteProductController,
    GetAllProductsController,
    GetOneProductController,
    UpdateProductController,
};
use App\Controllers\ProductType\{
    CreateProductTypeController,
    DeleteProductTypeController,
    GetAllProductTypesController,
    GetOneProductTypeController,
    UpdateProductTypeController
};
use App\Controllers\Sale\CreateSaleController;
use App\Controllers\Sale\GetAllSalesController;
use App\Controllers\Sale\GetOneSaleController;
use App\Helpers\Response;
use App\Services\ProductType\ProductTypeService;

class Router
{
    public function routeRequest()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Max-Age: 3600");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }


        switch ($uri) {
            case '/products':
                if ($method === 'GET') {
                    $controller = new GetAllProductsController();
                    $controller->__invoke();
                } elseif ($method === 'POST') {
                    $controller = new CreateProductController();
                    $controller->__invoke();
                } else {
                    Response::json(
                        Response::HTTP_BAD_REQUEST,
                        ["data" => "Page not found"]
                    );
                    break;
                }
                break;

            case (preg_match('/\/products\/(\d+)/', $uri, $matches) ? true : false):
                $id = $matches[1];
                switch ($method) {
                    case 'GET':
                        $controller = new GetOneProductController();
                        $controller->__invoke($id);
                        break;
                    case 'PUT':
                        $controller = new UpdateProductController();
                        $controller->__invoke($id);
                        break;
                    case 'DELETE':
                        $controller = new DeleteProductController();
                        $controller->__invoke($id);
                        break;
                    default:
                        Response::json(
                            Response::HTTP_BAD_REQUEST,
                            ["data" => "Page not found"]
                        );
                        break;
                }
                break;

            case '/product-types':
                if ($method === 'GET') {
                    $controller = new GetAllProductTypesController();
                    $controller->__invoke();
                } elseif ($method === 'POST') {
                    $controller = new CreateProductTypeController();
                    $controller->__invoke();
                } else {
                    Response::json(
                        Response::HTTP_BAD_REQUEST,
                        ["data" => "Page not found"]
                    );
                    break;
                }
                break;

            case (preg_match('/\/product-types\/(\d+)/', $uri, $matches) ? true : false):
                $productTypeId = $matches[1];
                switch ($method) {
                    case 'GET':
                        $controller = new GetOneProductTypeController();
                        $controller->__invoke($productTypeId);
                        break;
                    case 'PUT':
                        $controller = new UpdateProductTypeController();
                        $controller->__invoke($productTypeId);
                        break;
                    case 'DELETE':
                        $controller = new DeleteProductTypeController();
                        $controller->__invoke($productTypeId);
                        break;
                    default:
                        Response::json(
                            Response::HTTP_BAD_REQUEST,
                            ["data" => "Page not found"]
                        );
                        break;
                }
                break;
            case '/sales':
                if ($method === 'GET') {
                    $controller = new GetAllSalesController();
                    $controller->__invoke();
                } elseif ($method === 'POST') {
                    $controller = new CreateSaleController();
                    $controller->__invoke();
                } else {
                    Response::json(
                        Response::HTTP_BAD_REQUEST,
                        ["data" => "Page not found"]
                    );
                    break;
                }
                break;

            case (preg_match('/\/sales\/(\d+)/', $uri, $matches) ? true : false):
                $saleId = $matches[1];
                switch ($method) {
                    case 'GET':
                        $controller = new GetOneSaleController();
                        $controller->__invoke($saleId);
                        break;
                    // case 'PUT':
                    //     $controller = new UpdateProductTypeController();
                    //     $controller->__invoke($productTypeId);
                    //     break;
                    // case 'DELETE':
                    //     $controller = new DeleteProductTypeController();
                    //     $controller->__invoke($productTypeId);
                    //     break;
                    default:
                        Response::json(
                            Response::HTTP_BAD_REQUEST,
                            ["data" => "Page not found"]
                        );
                        break;
                }
                break;

            default:
                Response::json(
                    Response::HTTP_BAD_REQUEST,
                    ["data" => "Page not found"]
                );
                break;
        }
    }
}
