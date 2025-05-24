<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\CartItem;
use Models\Product;

class ShoppingCartRepository extends Repository
{

    public function addProduct(int $userId, $productId, int $quantity): void
    {
        try {
            // Check if the product is already in the cart for the given user
            $query = "SELECT quantity FROM cart_items WHERE user_id = :userId AND product_id = :productId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();

            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // If the item already exists, update the quantity
                $newQuantity = $existingItem['quantity'] + $quantity;
                $this->updateQuantity($userId, $productId, $newQuantity);
            } else {
                // If the item does not exist, insert it
                $query = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)";
                $stmt = $this->connection->prepare($query);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): void
    {
        try {
            $query = "UPDATE cart_items SET quantity = :quantity WHERE user_id = :userId AND product_id = :productId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    public function getShoppingCartByUserId(int $userId): array
    {
        try {
            $query = "
            SELECT ci.id, ci.product_id, ci.quantity, 
                   p.name AS product_name, p.price AS product_price, 
                   p.description AS product_description, p.image AS product_image, 
                   p.isFeatured AS is_featured
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.user_id = :userId AND p.isDeleted = 0
        ";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $cartItems = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new Product();
                $product->id = $row['product_id'];
                $product->name = $row['product_name'];
                $product->price = $row['product_price'];
                $product->description = $row['product_description'];
                $product->image = $row['product_image'];
                $product->isFeatured = (bool) $row['is_featured'];

                $cartItems[] = new CartItem($product, $row['quantity']);
            }

            return $cartItems;

        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return [];
        }
    }


    public function removeProduct(int $userId, int $productId): void
    {
        try {
            $query = "DELETE FROM cart_items WHERE user_id = :userId AND product_id = :productId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    public function clearCart(int $userId): void
    {
        try {
            $query = "DELETE FROM cart_items WHERE user_id = :userId";
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }
}
