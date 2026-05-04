<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id', 'invoice_number', 'total_price',
        'shipping_cost', 'total_weight', 'shipping_address',
        'payment_method', 'payment_status', 'status', 'notes',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ─── Custom Methods ──────────────────────────────────────

    public function getWithUser()
    {
        return $this->select('orders.*, users.full_name, users.email, users.phone')
                    ->join('users', 'users.id = orders.user_id');
    }

    public function getByUser(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getWithItems(int $orderId): array
    {
        $order = $this->select('orders.*, users.full_name, users.email, users.phone')
                      ->join('users', 'users.id = orders.user_id')
                      ->find($orderId);

        if ($order) {
            $order['items'] = model('OrderItemModel')->getItemsByOrder($orderId);
        }

        return $order ?? [];
    }

    public function generateInvoice(): string
    {
        $prefix = 'INV-' . date('Ymd');
        $last   = $this->like('invoice_number', $prefix)->orderBy('id', 'DESC')->first();

        if ($last) {
            $lastNum = (int) substr($last['invoice_number'], -5);
            $newNum  = str_pad($lastNum + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNum = '00001';
        }

        return $prefix . $newNum;
    }

    public function countByStatus(string $status): int
    {
        return $this->where('status', $status)->countAllResults();
    }

    public function totalRevenue(): float
    {
        $result = $this->selectSum('total_price')
                       ->where('payment_status', 'paid')
                       ->first();
        return (float) ($result['total_price'] ?? 0);
    }
}
