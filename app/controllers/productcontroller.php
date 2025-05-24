<?php

namespace Controllers;

use Exception;
use Services\ProductService;
use Models\Product;

class ProductController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new ProductService();
    }

    public function getAll()
    {
        try {
            $offset = NULL;
            $limit = NULL;

            if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
                $offset = $_GET["offset"];
            }
            if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
                $limit = $_GET["limit"];
            }

            $products = $this->service->getAll($offset, $limit);

            $this->respond($products);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getFeatured()
    {
        try {
            $products = $this->service->getFeatured();
            $this->respond($products);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getProductsByCategoryId($categoryId)
    {
        try {
            $products = $this->service->getProductsByCategoryId($categoryId);

            if (!$products) {
                $this->respondWithError(404, "No product not found");
                return;
            }

            $this->respond($products);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $product = $this->service->getOne($id);
            if (!$product) {
                $this->respondWithError(404, "Product not found");
                return;
            }

            $this->respond($product);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $decoded = $this->checkAuthorization(1); // 1 is the "admin" role ID
            if (!$decoded)
                return; // If the authorization failed, return

            $product = $this->createObjectFromPostedJson("Models\\Product");

            // Validate that required fields are not empty
            if (empty($product->name) || empty($product->description) || empty($product->category_id)) {
                throw new Exception("Name, description, and category cannot be empty.");
            }

            // Validate price format (should be a valid decimal, e.g., 99.99)
            if (!preg_match('/^\d+(\.\d{1,2})?$/', $product->price)) {
                throw new Exception("Price must be a valid number with up to two decimal places (e.g., 99.99).");
            }

            // Validate image format (must end with .jpeg or .png)
            if (!preg_match('/\.(jpg|png)$/i', $product->image)) {
                throw new Exception("Image must end with .jpeg or .png.");
            }
            $product->isFeatured = false;
            $product = $this->service->insert($product);

        } catch (Exception $e) {
            $this->respondWithError(400, $e->getMessage());
            return;
        }

        $this->respond($product);
    }

    public function update($id)
    {
        try {
            sleep(2);
            $product = $this->createObjectFromPostedJson("Models\\Product");
            $product = $this->service->update($product, $id);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($product);
    }



    public function delete($id)
    {
        try {
            $decoded = $this->checkAuthorization(1); // 1 is the "admin" role ID
            if (!$decoded)
                return; // If the authorization failed, return
            $this->service->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }

    public function setFeaturedStatus($id)
    {
        try {

            $decoded = $this->checkAuthorization(1); // 1 is the "admin" role ID
            if (!$decoded) {
                $this->respondWithError(403, "Unauthorized");
                return;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['isFeatured'])) {
                $this->respondWithError(400, "Missing 'isFeatured' field");
                return;
            }

            $isFeatured = $input['isFeatured']; // true or false
            $this->service->setFeaturedStatus($id, $isFeatured);

            $this->respond(true);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function searchProducts()
    {

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['searchValue'])) {
                $this->respondWithError(400, "Missing search value");
                return;
            }

            $searchValue = $input['searchValue'];

            // Update the featured status via the service
            $products = $this->service->searchProducts($searchValue);

            $this->respond($products);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

    }
}

