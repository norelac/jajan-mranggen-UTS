<?php

namespace App\Controllers\Pembeli;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Cart extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $cart  = session()->get('cart') ?? [];
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart));

        $data = [
            'title' => 'Keranjang Belanja - Jajan Mranggen',
            'cart'  => $cart,
            'total' => $total,
        ];
        return view('pembeli/cart/index', $data);
    }

    public function add()
    {
        $productId = (int) $this->request->getPost('product_id');
        $qty       = (int) $this->request->getPost('qty') ?: 1;

        $product = $this->productModel->find($productId);
        if (! $product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        if ($product['status'] !== 'active' || $product['stock'] < $qty) {
            return redirect()->back()->with('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
        }

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['qty'] + $qty;
            if ($newQty > $product['stock']) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart[$productId]['qty'] = $newQty;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name'       => $product['name'],
                'price'      => $product['price'],
                'image'      => $product['image'],
                'weight'     => $product['weight'],
                'qty'        => $qty,
                'stock'      => $product['stock'],
            ];
        }

        session()->set('cart', $cart);
        return redirect()->back()->with('success', "'{$product['name']}' berhasil ditambahkan ke keranjang!");
    }

    public function update()
    {
        $productId = (int) $this->request->getPost('product_id');
        $qty       = (int) $this->request->getPost('qty');

        $cart    = session()->get('cart') ?? [];
        $product = $this->productModel->find($productId);

        if (! isset($cart[$productId])) {
            return redirect()->back()->with('error', 'Item tidak ada di keranjang.');
        }

        if ($qty < 1) {
            unset($cart[$productId]);
        } elseif ($product && $qty <= $product['stock']) {
            $cart[$productId]['qty'] = $qty;
        } else {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        session()->set('cart', $cart);
        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(int $productId)
    {
        $cart = session()->get('cart') ?? [];
        unset($cart[$productId]);
        session()->set('cart', $cart);
        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->remove('cart');
        return redirect()->to('/pembeli/cart')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
