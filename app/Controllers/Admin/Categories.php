<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        helper('text');
    }

    public function index()
    {
        $data = [
            'title'      => 'Manajemen Kategori - Admin',
            'categories' => $this->categoryModel->withProductCount(),
        ];
        return view('admin/categories/index', $data);
    }

    public function create()
    {
        return view('admin/categories/create', ['title' => 'Tambah Kategori']);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'image' => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true);

        // Handle image upload
        $imagePath = null;
        $image     = $this->request->getFile('image');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            $newName   = $image->getRandomName();
            $image->move(FCPATH . 'uploads/categories', $newName);
            $imagePath = 'uploads/categories/' . $newName;
        }

        $this->categoryModel->save([
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'image'       => $imagePath,
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('admin/categories/edit', ['title' => 'Edit Kategori', 'category' => $category]);
    }

    public function update(int $id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'image' => 'is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');
        $slug = url_title($name, '-', true);

        $imagePath = $category['image'];
        $image     = $this->request->getFile('image');
        if ($image && $image->isValid() && ! $image->hasMoved()) {
            // Delete old image
            if ($imagePath && file_exists(FCPATH . $imagePath)) {
                unlink(FCPATH . $imagePath);
            }
            $newName   = $image->getRandomName();
            $image->move(FCPATH . 'uploads/categories', $newName);
            $imagePath = 'uploads/categories/' . $newName;
        }

        $this->categoryModel->update($id, [
            'name'        => $name,
            'slug'        => $slug,
            'description' => $this->request->getPost('description'),
            'image'       => $imagePath,
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);
        if (! $category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        if ($category['image'] && file_exists(FCPATH . $category['image'])) {
            unlink(FCPATH . $category['image']);
        }

        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
