<?php

namespace App\Controllers\Pembeli;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Orders extends BaseController
{
    protected OrderModel     $orderModel;
    protected OrderItemModel $orderItemModel;
    protected ProductModel   $productModel;

    public function __construct()
    {
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->productModel   = new ProductModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        $data = [
            'title'  => 'Pesanan Saya - Jajan Mranggen',
            'orders' => $this->orderModel->getByUser($userId),
        ];
        return view('pembeli/orders/index', $data);
    }

    public function show(int $id)
    {
        $userId = session()->get('userId');
        $order  = $this->orderModel->getWithItems($id);

        if (! $order || $order['user_id'] != $userId) {
            return redirect()->to('/pembeli/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Pesanan #' . $order['invoice_number'],
            'order' => $order,
        ];
        return view('pembeli/orders/show', $data);
    }

    public function checkout()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/pembeli/cart')->with('error', 'Keranjang belanja kosong.');
        }

        $rules = [
            'shipping_address' => 'required|min_length[10]',
            'payment_method'   => 'required|in_list[transfer_bank,cod,dompet_digital]',
        ];

        $messages = [
            'shipping_address' => ['required' => 'Alamat pengiriman wajib diisi', 'min_length' => 'Alamat terlalu pendek'],
            'payment_method'   => ['required' => 'Metode pembayaran wajib dipilih'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('userId');

        // Calculate totals
        $totalPrice  = 0;
        $totalWeight = 0;
        $itemsToSave = [];

        foreach ($cart as $item) {
            $product = $this->productModel->find($item['product_id']);
            if (! $product || $product['stock'] < $item['qty']) {
                return redirect()->to('/pembeli/cart')->with('error', "Stok produk '{$item['name']}' tidak mencukupi.");
            }
            $subtotal     = $product['price'] * $item['qty'];
            $totalPrice  += $subtotal;
            $totalWeight += $product['weight'] * $item['qty'];
            $itemsToSave[] = [
                'product_id' => $item['product_id'],
                'quantity'   => $item['qty'],
                'price'      => $product['price'],
                'subtotal'   => $subtotal,
            ];
        }

        $shippingCost = $this->calculateShipping($totalWeight);
        $invoice      = $this->orderModel->generateInvoice();

        // Insert order
        $this->orderModel->save([
            'user_id'          => $userId,
            'invoice_number'   => $invoice,
            'total_price'      => $totalPrice + $shippingCost,
            'shipping_cost'    => $shippingCost,
            'total_weight'     => $totalWeight,
            'shipping_address' => $this->request->getPost('shipping_address'),
            'payment_method'   => $this->request->getPost('payment_method'),
            'payment_status'   => 'pending',
            'status'           => 'pending',
            'notes'            => $this->request->getPost('notes'),
        ]);

        $orderId = $this->orderModel->getInsertID();

        // Insert order items & decrease stock
        foreach ($itemsToSave as &$item) {
            $item['order_id'] = $orderId;
            $this->productModel->decreaseStock($item['product_id'], $item['quantity']);
        }
        $this->orderItemModel->insertBatch($itemsToSave);

        // Clear cart
        session()->remove('cart');

        return redirect()->to('/pembeli/orders/' . $orderId)
                         ->with('success', 'Pesanan berhasil dibuat! No. Invoice: ' . $invoice);
    }

    public function cancel(int $id)
    {
        $userId = session()->get('userId');
        $order  = $this->orderModel->find($id);

        if (! $order || $order['user_id'] != $userId) {
            return redirect()->to('/pembeli/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        if (! in_array($order['status'], ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        // Return stock
        $items = $this->orderItemModel->getItemsByOrder($id);
        foreach ($items as $item) {
            $this->productModel->increaseStock($item['product_id'], $item['quantity']);
        }

        $this->orderModel->update($id, ['status' => 'cancelled']);
        return redirect()->to('/pembeli/orders/' . $id)->with('success', 'Pesanan berhasil dibatalkan.');
    }

    private function calculateShipping(int $weightGram): float
    {
        $kg = $weightGram / 1000;
        if ($kg <= 1)  return 10000;
        if ($kg <= 5)  return 20000;
        return 35000;
    }
}
