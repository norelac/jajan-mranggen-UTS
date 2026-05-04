<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'order_id', 'product_id', 'quantity', 'price', 'subtotal',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ─── Custom Methods ──────────────────────────────────────

    public function getItemsByOrder(int $orderId): array
    {
        return $this->select('order_items.*, products.name as product_name, products.image as product_image, products.seller_id, users.full_name as seller_name')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('users', 'users.id = products.seller_id')
                    ->where('order_items.order_id', $orderId)
                    ->findAll();
    }

    public function getItemsBySeller(int $sellerId): array
    {
        return $this->select('order_items.*, products.name as product_name, orders.invoice_number, orders.status as order_status, orders.created_at as order_date, users.full_name as buyer_name')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->join('users', 'users.id = orders.user_id')
                    ->where('products.seller_id', $sellerId)
                    ->orderBy('order_items.created_at', 'DESC')
                    ->findAll();
    }

    public function getTotalSoldBySeller(int $sellerId): float
    {
        $result = $this->selectSum('order_items.subtotal')
                       ->join('products', 'products.id = order_items.product_id')
                       ->join('orders', 'orders.id = order_items.order_id')
                       ->where('products.seller_id', $sellerId)
                       ->where('orders.payment_status', 'paid')
                       ->first();
        return (float) ($result['subtotal'] ?? 0);
    }
}
