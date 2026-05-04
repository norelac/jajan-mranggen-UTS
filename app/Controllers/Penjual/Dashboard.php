<?php

namespace App\Controllers\Penjual;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $sellerId        = session()->get('userId');
        $productModel    = new ProductModel();
        $orderItemModel  = new OrderItemModel();

        $products = $productModel->getBySeller($sellerId);
        $items    = $orderItemModel->getItemsBySeller($sellerId);
        $revenue  = $orderItemModel->getTotalSoldBySeller($sellerId);

        $totalStock = array_sum(array_column($products, 'stock'));

        $data = [
            'title'         => 'Dashboard Penjual - Jajan Mranggen',
            'totalProducts' => count($products),
            'totalOrders'   => count(array_unique(array_column($items, 'order_id') ?: [])),
            'totalRevenue'  => $revenue,
            'totalStock'    => $totalStock,
            'recentItems'   => array_slice($items, 0, 5),
        ];

        return view('penjual/dashboard', $data);
    }
}
