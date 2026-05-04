<?php

namespace App\Controllers\Penjual;

use App\Controllers\BaseController;
use App\Models\OrderItemModel;
use App\Models\OrderModel;

class Orders extends BaseController
{
    protected OrderItemModel $orderItemModel;
    protected OrderModel     $orderModel;

    public function __construct()
    {
        $this->orderItemModel = new OrderItemModel();
        $this->orderModel     = new OrderModel();
    }

    public function index()
    {
        $sellerId = session()->get('userId');
        $items    = $this->orderItemModel->getItemsBySeller($sellerId);

        // Group by order
        $orders = [];
        foreach ($items as $item) {
            $key = $item['order_id'];
            if (! isset($orders[$key])) {
                $orders[$key] = [
                    'order_id'       => $item['order_id'] ?? null,
                    'invoice_number' => $item['invoice_number'],
                    'order_status'   => $item['order_status'],
                    'order_date'     => $item['order_date'],
                    'buyer_name'     => $item['buyer_name'],
                    'items'          => [],
                    'subtotal'       => 0,
                ];
            }
            $orders[$key]['items'][]  = $item;
            $orders[$key]['subtotal'] += $item['subtotal'];
        }

        $data = [
            'title'  => 'Pesanan Masuk - Penjual',
            'orders' => array_values($orders),
        ];
        return view('penjual/orders/index', $data);
    }

    public function show(int $orderId)
    {
        $sellerId = session()->get('userId');
        $order    = $this->orderModel->getWithItems($orderId);

        if (! $order) {
            return redirect()->to('/penjual/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        // Filter items to only show this seller's items
        $order['items'] = array_filter($order['items'], fn($item) => $item['seller_id'] == $sellerId);

        $data = [
            'title' => 'Detail Pesanan #' . $order['invoice_number'],
            'order' => $order,
        ];
        return view('penjual/orders/show', $data);
    }

    public function updateStatus(int $orderId)
    {
        $order = $this->orderModel->find($orderId);
        if (! $order) {
            return redirect()->to('/penjual/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $status = $this->request->getPost('status');
        $allowedStatuses = ['processing', 'shipped'];

        if (! in_array($status, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->orderModel->update($orderId, ['status' => $status]);
        return redirect()->to('/penjual/orders/' . $orderId)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
