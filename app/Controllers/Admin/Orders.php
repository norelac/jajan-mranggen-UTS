<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class Orders extends BaseController
{
    protected OrderModel $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');

        $builder = $this->orderModel->getWithUser()->orderBy('orders.created_at', 'DESC');
        if ($status) {
            $builder->where('orders.status', $status);
        }

        $data = [
            'title'  => 'Manajemen Pesanan - Admin',
            'orders' => $builder->findAll(),
            'status' => $status,
        ];
        return view('admin/orders/index', $data);
    }

    public function show(int $id)
    {
        $order = $this->orderModel->getWithItems($id);
        if (! $order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Pesanan #' . $order['invoice_number'],
            'order' => $order,
        ];
        return view('admin/orders/show', $data);
    }

    public function updateStatus(int $id)
    {
        $order = $this->orderModel->find($id);
        if (! $order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $status        = $this->request->getPost('status');
        $paymentStatus = $this->request->getPost('payment_status');

        $updateData = [];
        if ($status)        $updateData['status']         = $status;
        if ($paymentStatus) $updateData['payment_status'] = $paymentStatus;

        $this->orderModel->update($id, $updateData);
        return redirect()->to('/admin/orders/' . $id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
