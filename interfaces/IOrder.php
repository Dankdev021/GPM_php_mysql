<?php
// interfaces/IOrder.php

interface IOrder {
    public function create($user_id, $seller_id, $product_id, $quantity);
    public function getAll();
    public function getById($id);
    public function getByUserId($user_id);
}
?>
