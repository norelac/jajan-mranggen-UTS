<?php

namespace App\Controllers\Pembeli;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId      = session()->get('userId');
        $orderModel  = new OrderModel();
        $productModel = new ProductModel();

        $orders      = $orderModel->getByUser($userId);
        $totalOrders = count($orders);
        $totalSpent  = array_sum(array_column(
            array_filter($orders, fn($o) => $o['payment_status'] === 'paid'),
            'total_price'
        ));

        $data = [
            'title'         => 'Dashboard Pembeli - Jajan Mranggen',
            'totalOrders'   => $totalOrders,
            'totalSpent'    => $totalSpent,
            'recentOrders'  => array_slice($orders, 0, 5),
            'featuredProducts' => $productModel->getActiveProducts(),
        ];

        return view('pembeli/dashboard', $data);
    }
}
