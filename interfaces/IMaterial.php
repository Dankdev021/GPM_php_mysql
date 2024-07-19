<?php
// interfaces/IMaterial.php

interface IMaterial {
    public function create($name, $description, $quantity, $price, $total_price);
    public function getAll();
    public function getById($id);
    public function update($id, $name, $description, $quantity, $price, $total_price);
    public function delete($id);
}
?>
