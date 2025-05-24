<?php
namespace Services;

use Repositories\ShoppingCartRepository;

class ShoppingCartService
{

    private $repository;

    function __construct()
    {
        $this->repository = new ShoppingCartRepository();
    }

    public function getShoppingCartByUserId($id)
    {
        return $this->repository->getShoppingCartByUserId($id);
    }

    public function addToCart(int $userId, int $productId, int $quantity)
    {
        return $this->repository->addProduct($userId, $productId, $quantity);
    }

    public function updateQuantity(int $userId, int $productId, int $quantity)
    {
        return $this->repository->updateQuantity($userId, $productId, $quantity);
    }

    public function removeProduct(int $userId, int $productId)
    {
        $this->repository->removeProduct($userId, $productId);
    }

    public function clearCart(int $userId)
    {
        $this->repository->clearCart($userId);
    }


}

