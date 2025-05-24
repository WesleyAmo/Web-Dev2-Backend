<?php
namespace Services;

use Repositories\OrderRepository;

class OrderService
{

    private $repository;

    function __construct()
    {
        $this->repository = new OrderRepository();
    }

    public function getAll()
    {
        return $this->repository->getAllOrders();
    }

    public function create($id)
    {
        return $this->repository->saveOrder($id);
    }

    public function update($item, $id)
    {
        return $this->repository->update($item, $id);
    }

}

?>