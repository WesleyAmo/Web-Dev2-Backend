<?php

namespace Controllers;

use Exception;
use Services\OrderService;
use Models\Order;
use Models\Order_Item;
use Models\User;
use Models\Product;

class OrderController extends Controller
{
    private $service;

    function __construct()
    {
        $this->service = new OrderService();
    }

    public function getAll()
    {
        try {
            $decoded = $this->checkAuthorization(1); // 1 is the "admin" role ID
            if (!$decoded) {
                $this->respondWithError(403, "Unauthorized");
                return;
            }
            $categories = $this->service->getAll();

            $this->respond($categories);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function create($userId)
    {
        try {
            $decodedToken = $this->checkAuthorization(null, $userId);
            if (!$decodedToken) {
                return; // Authorization failed, response already sent
            }
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate required fields
            if (!isset($data['user']['id']) || empty($data['items'])) {
                $this->respondWithError(400, "User ID and at least one order item are required");
                return;
            }

            // Create Order object
            $order = new Order();
            $order->user = new User();
            $order->user->id = (int) $data['user']['id'];
            $order->total_amount = $data['total_amount'] ?? 0; // Can be calculated server-side
            $order->status = $data['status'] ?? 'pending';
            $order->items = [];

            // Create Order Items
            foreach ($data['items'] as $itemData) {
                if (!isset($itemData['product']['id']) || !isset($itemData['quantity'])) {
                    $this->respondWithError(400, "Each item must have a product ID and quantity");
                    return;
                }

                $item = new Order_Item();
                $item->product = new Product();
                $item->product->id = (int) $itemData['product']['id'];
                $item->quantity = (int) $itemData['quantity'];
                $order->items[] = $item;
            }

            // Save the order
            $order = $this->service->create($order);

            $this->respond($order);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $decoded = $this->checkAuthorization(1); // 1 is the "admin" role ID
            if (!$decoded) {
                $this->respondWithError(403, "Unauthorized");
                return;
            }
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate required fields

            $id = $data['order_id'];
            $status = $data['status'];
            $updated = $this->service->update($id, $status);

            $this->respond([
                'success' => $updated
            ]);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

    }
}
