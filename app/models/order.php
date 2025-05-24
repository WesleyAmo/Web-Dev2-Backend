<?php
namespace Models;

class Order
{

    public int $id;
    public User $user;
    public $order_date;
    public $total_amount;
    public string $status;
    public $items;
}
