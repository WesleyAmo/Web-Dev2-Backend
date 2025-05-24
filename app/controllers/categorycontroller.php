<?php

namespace Controllers;

use Exception;
use Services\CategoryService;

class CategoryController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new CategoryService();
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

            $categories = $this->service->getAll($offset, $limit);

            $this->respond($categories);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function getOne($id)
    {
        try {
            $category = $this->service->getOne($id);

            if (!$category) {
                $this->respondWithError(404, "Category not found");
                return;
            }

            $this->respond($category);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
