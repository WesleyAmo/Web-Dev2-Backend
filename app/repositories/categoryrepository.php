<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class CategoryRepository extends Repository
{
    function getAll($offset = NULL, $limit = NULL)
    {
        try {
            $query = "SELECT * FROM categories";
            if (isset($limit) && isset($offset)) {
                $query .= " LIMIT :limit OFFSET :offset ";
            }
            $stmt = $this->connection->prepare($query);
            if (isset($limit) && isset($offset)) {
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Category');
            $catgs = $stmt->fetchAll();

            return $catgs;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return null;
        }
    }

    function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM category WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\Category');
            $product = $stmt->fetch();

            return $product;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    function insert($category)
    {
        try {
            $stmt = $this->connection->prepare("INSERT into category (name) VALUES (?)");
            $stmt->execute([$category->name]);
            $category->id = $this->connection->lastInsertId();

            return $category;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }


    function update($category, $id)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE category SET name = ? WHERE id = ?");

            $stmt->execute([$category->name, $id]);

            return $category;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    function delete($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM category WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
        return true;
    }
}
