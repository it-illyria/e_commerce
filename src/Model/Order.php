<?php

namespace App\Model;

use App\Database\Database;
use mysqli;

class Order
{
    private ?mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    // Create a new order
    public function createOrder($userId, $address, $total, $phone): bool
    {
        if ($userId === NULL) {
            $query = "INSERT INTO guest_orders (address, total, status, payment_method, phone) 
                  VALUES (?, ?, 'pending', 'cash', ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sds", $address, $total, $phone);
        } else {
            $query = "INSERT INTO orders (user_id, address, total, status, payment_method, phone) 
                  VALUES (?, ?, ?, 'pending', 'cash', ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("isds", $userId, $address, $total, $phone);
        }

        return $stmt->execute();
    }

    public function updateOrderStatus($orderId, $status): bool
    {
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }


}
