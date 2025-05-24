<?php

namespace Controllers;

use Exception;
use Services\ProductService;
use Models\Product;
use Services\ShoppingCartService;

class CartController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new ShoppingCartService();
    }

    public function getCart($id)
    {
        try {
            $decodedToken = $this->checkAuthorization(null, $id);
            if (!$decodedToken) {
                return; // Authorization failed, response already sent
            }

            $cartitems = $this->service->getShoppingCartByUserId($id);


            $this->respond($cartitems);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function addToCart($productId)
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['user_id'])) {
                $this->respondWithError(400, "Missing user");
                return;
            }
            if (!isset($input['quantity'])) {
                $this->respondWithError(400, "Missing quantity");
                return;
            }

            $user_id = $input['user_id'];
            $quantity = $input['quantity'];
            $decodedToken = $this->checkAuthorization(null, $user_id); // Pass the userId to ensure only admins or the user themselves can update
            if (!$decodedToken) {
                return;
            }

            $this->service->addToCart($user_id, $productId, $quantity);
            $this->respond(true);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function updateQuantity($productId)
    {
        try {
            // Get the JSON body data
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['user_id'])) {
                $this->respondWithError(400, "Missing user");
                return;
            }
            if (!isset($input['quantity'])) {
                $this->respondWithError(400, "Missing quantity");
                return;
            }



            $user_id = $input['user_id'];
            $quantity = $input['quantity'];
            $decodedToken = $this->checkAuthorization(null, $user_id); // Pass the userId to ensure only admins or the user themselves can update
            if (!$decodedToken) {
                return; // Authorization failed, response already sent
            }

            if ($quantity < 1) {
                $this->service->removeProduct($user_id, $productId);
            } else {
                $this->service->updateQuantity($user_id, $productId, $quantity);
            }


            $this->respond(true);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function removeProduct($productId)
    {
        try {

            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['user_id'])) {
                $this->respondWithError(400, "Missing user");
                return;
            }

            $user_id = $input['user_id'];
            $decodedToken = $this->checkAuthorization(null, $user_id);
            if (!$decodedToken) {
                return;
            }
            $this->service->removeProduct($user_id, $productId);
            $this->respond(true);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function clear()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['user_id'])) {
                $this->respondWithError(400, "Missing user");
                return;
            }

            $user_id = $input['user_id'];
            $decodedToken = $this->checkAuthorization(null, $user_id);
            if (!$decodedToken) {
                return;
            }
            $this->service->clearCart($user_id);
            $this->respond(true);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

}

