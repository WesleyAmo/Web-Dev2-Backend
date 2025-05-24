<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Order;
use Models\Order_Item;
use Models\User;
use Models\Product;
use Models\Category;

class OrderRepository extends Repository
{
    public function saveOrder(Order $order): int
    {
        $this->connection->beginTransaction();

        try {
            // Save the order
            $orderId = $this->insertOrder($order);

            if ($orderId === 0) {
                throw new PDOException('Failed to save order');
            }

            // Save each order item
            foreach ($order->items as $item) {
                $item->id = $orderId;
                $this->insertOrderItem($item);
            }
            $this->clearCart($order->user->id);
            $this->connection->commit();
            return $orderId;

        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log('Order save failed: ' . $e->getMessage());
            return 0;
        }
    }

    private function insertOrder(Order $order): int
    {
        $query = "INSERT INTO orders (user_id, total_amount, status) 
                  VALUES (:userId, :totalAmount, :status)";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':userId', $order->user->id, PDO::PARAM_INT);
            $stmt->bindValue(':totalAmount', $order->total_amount);
            $stmt->bindValue(':status', $order->status);
            $stmt->execute();

            return (int) $this->connection->lastInsertId();

        } catch (PDOException $e) {
            error_log('Insert order failed: ' . $e->getMessage());
            return 0;
        }
    }

    private function insertOrderItem(Order_Item $item): bool
    {
        $query = "INSERT INTO order_items (order_id, product_id, quantity) 
                  VALUES (:orderId, :productId, :quantity)";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':orderId', $item->id, PDO::PARAM_INT);
            $stmt->bindValue(':productId', $item->product->id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $item->quantity, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log('Insert order item failed: ' . $e->getMessage());
            return false;
        }
    }

    private function clearCart(int $userId): bool
    {
        $query = "DELETE FROM cart_items WHERE user_id = :userId";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log('Cart clearing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getAllOrders(): array
    {
        try {
            $query = "
                SELECT 
                    o.id, o.user_id, o.total_amount, o.status AS order_status, o.order_date,
                    u.id AS user_id, u.username, u.full_name, u.email, u.phone,
                    oi.id AS item_id, oi.product_id, oi.quantity,
                    p.id AS product_id, p.name AS product_name, p.price AS product_price, 
                    p.image AS product_image
                FROM orders o
                JOIN users u ON o.user_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.id
                ORDER BY o.order_date DESC
            ";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $orders = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $orderId = $row['id'];

                if (!isset($orders[$orderId])) {
                    $order = new Order();
                    $order->id = $orderId;
                    $order->order_date = $row['order_date'];
                    $order->total_amount = $row['total_amount'];
                    $order->status = $row['order_status'];

                    $user = new User();
                    $user->id = $row['user_id'];
                    $user->username = $row['username'];
                    $user->fullName = $row['full_name'];
                    $user->email = $row['email'];
                    $user->phone = $row['phone'];
                    $order->user = $user;

                    $orders[$orderId] = $order;
                }

                if ($row['item_id']) {
                    $orderItem = new Order_Item();
                    $orderItem->id = $row['item_id'];
                    $orderItem->id = $orderId;
                    $orderItem->quantity = $row['quantity'];

                    $product = new Product();
                    $product->id = $row['product_id'];
                    $product->name = $row['product_name'];
                    $product->price = $row['product_price'];
                    $product->image = $row['product_image'];

                    $orderItem->product = $product;

                    $orders[$orderId]->items[] = $orderItem;
                }
            }

            return array_values($orders);
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return [];
        }
    }

    public function update(int $orderId, string $newStatus): bool
    {
        try {
            $query = "UPDATE orders SET status = :status WHERE id = :orderId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $stmt->rowCount() > 0;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Order update error: ' . $e->getMessage());
            return false;
        }
    }
}