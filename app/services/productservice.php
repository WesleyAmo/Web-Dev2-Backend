<?php
namespace Services;

use Repositories\ProductRepository;

class ProductService
{

    private $repository;

    function __construct()
    {
        $this->repository = new ProductRepository();
    }

    public function getAll($offset = NULL, $limit = NULL)
    {
        return $this->repository->getAll($offset, $limit);
    }

    public function getFeatured($offset = NULL, $limit = NULL)
    {
        return $this->repository->getFeatured();
    }

    public function getOne($id)
    {
        return $this->repository->getOne($id);
    }

    public function insert($item)
    {
        return $this->repository->create($item);
    }

    public function update($item, $id)
    {
        return $this->repository->update($item, $id);
    }

    public function delete($item)
    {
        return $this->repository->delete($item);
    }

    public function getProductsByCategoryId($categoryId)
    {
        return $this->repository->getProductsByCategoryId($categoryId);
    }

    public function setFeaturedStatus($id, $isFeatured)
    {
        return $this->repository->setFeaturedStatus($id, $isFeatured);
    }

    public function searchProducts($searchValue)
    {
        return $this->repository->searchProducts($searchValue);
    }
}

?>