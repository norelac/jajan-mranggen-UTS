<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\CategoryModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel     = new UserModel();
        $productModel  = new ProductModel();
        $orderModel    = new OrderModel();
        $categoryModel = new CategoryModel();

        $data = [
            'title'          => 'Dashboard Admin - Jajan Mranggen',
            'totalUsers'     => $userModel->countAllResults(),
            'totalPenjual'   => $userModel->countByRole('penjual'),
            'totalPembeli'   => $userModel->countByRole('pembeli'),
            'totalProducts'  => $productModel->where('deleted_at', null)->countAllResults(),
            'totalOrders'    => $orderModel->countAllResults(),
            'pendingOrders'  => $orderModel->countByStatus('pending'),
            'totalRevenue'   => $orderModel->totalRevenue(),
            'totalCategories'=> $categoryModel->countAllResults(),
            'recentOrders'   => $orderModel->getWithUser()->orderBy('orders.created_at', 'DESC')->limit(5)->findAll(),
        ];

        return view('admin/dashboard', $data);
    }
}
