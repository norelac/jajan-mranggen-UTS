<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\UserModel;

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
        $data = [
            'title'    => 'Manajemen Produk - Admin',
            'products' => $this->productModel->getWithDetails()->withDeleted()->findAll(),
        ];
        return view('admin/products/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Produk',
            'categories' => $this->categoryModel->findAll(),
            'sellers'    => (new UserModel())->getByRole('penjual'),
        ];
        return view('admin/products/create', $data);
    }

    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[3]|max_length[200]',
            'category_id' => 'required|integer',
            'seller_id'   => 'required|integer',
            'price'       => 'required|decimal|greater_than[0]',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'weight'      => 'required|integer|greater_than[0]',
            'image'       => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true);

        $imagePath = null;
        $image     = $this->request->getFile('image');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/products', $newName);
            $imagePath = 'uploads/products/' . $newName;
        }

        $this->productModel->save([
            'category_id' => $this->request->getPost('category_id'),
            'seller_id'   => $this->request->getPost('seller_id'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'weight'      => $this->request->getPost('weight'),
            'image'       => $imagePath,
            'status'      => $this->request->getPost('status') ?? 'active',
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'categories' => $this->categoryModel->findAll(),
            'sellers'    => (new UserModel())->getByRole('penjual'),
        ];
        return view('admin/products/edit', $data);
    }

    public function update(int $id)
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[200]',
            'category_id' => 'required|integer',
            'seller_id'   => 'required|integer',
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
            'seller_id'   => $this->request->getPost('seller_id'),
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'stock'       => $this->request->getPost('stock'),
            'weight'      => $this->request->getPost('weight'),
            'image'       => $imagePath,
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->to('/admin/products')->with('error', 'Produk tidak ditemukan.');
        }

        $this->productModel->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dihapus.');
    }
}
