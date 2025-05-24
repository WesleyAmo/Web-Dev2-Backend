<?php

namespace Repositories;

use Models\Category;
use Models\Product;
use PDO;
use PDOException;
use Repositories\Repository;

class ProductRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT products.*, categories.name as category_name FROM products 
                      INNER JOIN categories ON products.category_id = categories.id
                      WHERE products.isDeleted = 0";

            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);

            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $products = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            error_log("getAll error: " . $e->getMessage());
            return [];
        }
    }

    function getFeatured()
    {
        try {
            $query = "SELECT products.*, categories.name as category_name FROM products 
                      INNER JOIN categories ON products.category_id = categories.id
                      WHERE products.isFeatured = 1 AND products.isDeleted = 0";

            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            $products = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToProduct($row);
            }

            return $products;
        } catch (PDOException $e) {
            error_log("getFeatured error: " . $e->getMessage());
            return [];
        }
    }

    function getOne($id)
    {
        try {
            $query = "SELECT products.*, categories.name as category_name FROM products 
                      INNER JOIN categories ON products.category_id = categories.id 
                      WHERE products.id = :id AND products.isDeleted = 0";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row)
                return null;
            return $this->rowToProduct($row);
        } catch (PDOException $e) {
            error_log("getOne error: " . $e->getMessage());
            return null;
        }
    }

    function rowToProduct($row)
    {
        $product = new Product();
        $product->id = $row['id'];
        $product->name = $row['name'];
        $product->price = $row['price'];
        $product->description = $row['description'];
        $product->image = $row['image'];
        $product->isFeatured = $row['isFeatured'];
        $product->category_id = $row['category_id'];
        $category = new Category();
        $category->id = $row['category_id'];
        $category->name = $row['category_name'];

        $product->category = $category;
        return $product;
    }


    function update($product, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE product SET name = ?, price = ?, description = ?, image = ?, category_id = ? WHERE id = ?");

            $stmt->execute([$product->name, $product->price, $product->description, $product->image, $product->category_id, $id]);

            return $this->getOne($product->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE products SET isDeleted = true WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Delete error: " . $e->getMessage());
            return false;
        }
    }

    public function create(Product $product): ?Product
    {
        try {
            $stmt = $this->connection->prepare(
                'INSERT INTO products (name, price, description, image, category_id, isFeatured) 
             VALUES (:name, :price, :description, :image, :categoryId, :isFeatured)'
            );

            $stmt->bindParam(':name', $product->name);
            $stmt->bindParam(':price', $product->price);
            $stmt->bindParam(':description', $product->description);
            $stmt->bindParam(':image', $product->image);
            $stmt->bindParam(':categoryId', $product->category_id);
            $stmt->bindValue(':isFeatured', $product->isFeatured ?? 0, PDO::PARAM_INT);


            $stmt->execute();
            $product->id = $this->connection->lastInsertId();

            return $product;

        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getProductsByCategoryId($categoryId)
    {
        try {
            $query = "SELECT products.*, categories.name as category_name 
                  FROM products 
                  INNER JOIN categories ON products.category_id = categories.id 
                  WHERE products.category_id = :categoryId AND products.isDeleted = 0";

            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->execute();

            $products = [];
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToProduct($row);
            }

            return $products;

        } catch (PDOException $e) {
            error_log("getProductsByCategoryId error: " . $e->getMessage());
            return null;
        }
    }

    public function setFeaturedStatus($id, $isFeatured)
    {
        try {
            $query = "UPDATE products SET isFeatured = :isFeatured WHERE id = :id";
            $stmt = $this->connection->prepare($query);

            $stmt->bindParam(':isFeatured', $isFeatured, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();
            return $this->getOne($id);

        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return null;
        }
    }

    public function searchProducts($searchValue)
    {
        try {
            // Search for products by name or description, excluding deleted ones
            $query = "SELECT products.*, categories.name as category_name 
                  FROM products 
                  INNER JOIN categories ON products.category_id = categories.id 
                  WHERE (products.name LIKE :searchValue OR products.description LIKE :searchValue)
                  AND products.isDeleted = 0";

            $stmt = $this->connection->prepare($query);

            $searchTerm = '%' . $searchValue . '%';
            $stmt->bindParam(':searchValue', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();

            $products = [];
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
                $products[] = $this->rowToProduct($row);
            }

            return $products;

        } catch (PDOException $e) {
            error_log("searchProducts error: " . $e->getMessage());
            return null;
        }
    }


}
