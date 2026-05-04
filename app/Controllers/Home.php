<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected ProductModel  $productModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Jajan Mranggen - Jajanan Khas Mranggen',
            'products'   => $this->productModel->getActiveProducts(),
            'categories' => $this->categoryModel->withProductCount(),
        ];
        return view('home/index', $data);
    }

    public function products()
    {
        $keyword    = $this->request->getGet('q');
        $categoryId = $this->request->getGet('category');

        if ($keyword) {
            $products = $this->productModel->searchProducts($keyword, $categoryId);
        } elseif ($categoryId) {
            $products = $this->productModel->getWithDetails()
                             ->where('products.status', 'active')
                             ->where('products.category_id', $categoryId)
                             ->findAll();
        } else {
            $products = $this->productModel->getActiveProducts();
        }

        $data = [
            'title'      => 'Semua Produk - Jajan Mranggen',
            'products'   => $products,
            'categories' => $this->categoryModel->findAll(),
            'keyword'    => $keyword,
            'categoryId' => $categoryId,
        ];
        return view('home/products', $data);
    }

    public function productDetail(string $slug)
    {
        $product = $this->productModel->findBySlug($slug);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan');
        }

        $related = $this->productModel->getWithDetails()
                        ->where('products.category_id', $product['category_id'])
                        ->where('products.id !=', $product['id'])
                        ->where('products.status', 'active')
                        ->limit(4)
                        ->findAll();

        $data = [
            'title'   => $product['name'] . ' - Jajan Mranggen',
            'product' => $product,
            'related' => $related,
        ];
        return view('home/product_detail', $data);
    }

    public function category(string $slug)
    {
        $category = $this->categoryModel->findBySlug($slug);
        if (! $category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kategori tidak ditemukan');
        }

        $products = $this->productModel->getWithDetails()
                         ->where('products.category_id', $category['id'])
                         ->where('products.status', 'active')
                         ->findAll();

        $data = [
            'title'    => $category['name'] . ' - Jajan Mranggen',
            'category' => $category,
            'products' => $products,
        ];
        return view('home/category', $data);
    }
}
