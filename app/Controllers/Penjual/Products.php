<?php

namespace App\Controllers\Penjual;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected ProductModel  $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        helper('text');
    }

    public function index()
    {
        $sellerId = session()->get('userId');
        $data = [
            'title'    => 'Produk Saya - Penjual',
            'products' => $this->productModel->getBySeller($sellerId),
        ];
        return view('penjual/products/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Produk',
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('penjual/products/create', $data);
    }

    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[200]',
            'category_id' => 'required|integer',
            'price'       => 'required|decimal|greater_than[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'weight'      => 'required|integer|greater_than[0]',
            'image'       => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        $messages = [
            'name'        => ['required' => 'Nama produk wajib diisi'],
            'category_id' => ['required' => 'Kategori wajib dipilih'],
            'price'       => ['required' => 'Harga wajib diisi', 'greater_than' => 'Harga harus lebih dari 0'],
            'stock'       => ['required' => 'Stok wajib diisi'],
            'weight'      => ['required' => 'Berat wajib diisi'],
            'image'       => ['is_image' => 'File harus berupa gambar', 'max_size' => 'Ukuran gambar maksimal 2MB'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true);

        // Check slug uniqueness
        $existing = $this->productModel->where('slug', $slug)->first();
        if ($existing) {
            $slug = $slug . '-' . time();
        }

        $imagePath = null;
        $image     = $this->request->getFile('image');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imagePath = 'uploads/products/' . $newName;
        }

        $this->productModel->save([
            'category_id' => $this->request->getPost('category_id'),
            'seller_id'   => session()->get('userId'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'weight'      => $this->request->getPost('weight'),
            'image'       => $imagePath,
            'status'      => $this->request->getPost('status') ?? 'active',
        ]);

        return redirect()->to('/penjual/products')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $product = $this->productModel->find($id);
        if (! $product || $product['seller_id'] != session()->get('userId')) {
            return redirect()->to('/penjual/products')->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
        ];
        return view('penjual/products/edit', $data);
    }

    public function update(int $id)
    {
        $product = $this->productModel->find($id);
        if (! $product || $product['seller_id'] != session()->get('userId')) {
            return redirect()->to('/penjual/products')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[200]',
            'category_id' => 'required|integer',
            'price'       => 'required|decimal|greater_than[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'weight'      => 'required|integer|greater_than[0]',
            'image'       => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name      = $this->request->getPost('name');
        $slug      = url_title($name, '-', true);
        $imagePath = $product['image'];
        $image     = $this->request->getFile('image');

        if ($image && $image->isValid() && ! $image->hasMoved()) {
            if ($imagePath && file_exists(FCPATH . $imagePath)) {
                unlink(FCPATH . $imagePath);
            }
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imagePath = 'uploads/products/' . $newName;
        }

        $this->productModel->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'weight'      => $this->request->getPost('weight'),
            'image'       => $imagePath,
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/penjual/products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function delete(int $id)
    {
        $product = $this->productModel->find($id);
        if (! $product || $product['seller_id'] != session()->get('userId')) {
            return redirect()->to('/penjual/products')->with('error', 'Produk tidak ditemukan.');
        }

        $this->productModel->delete($id);
        return redirect()->to('/penjual/products')->with('success', 'Produk berhasil dihapus.');
    }
}
