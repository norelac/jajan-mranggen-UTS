<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\ProductModel;

class Reviews extends BaseController
{
    protected ReviewModel $reviewModel;
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->reviewModel  = new ReviewModel();
        $this->productModel = new ProductModel();
    }

    /**
     * POST /reviews/store
     * Pembeli submits a review for a product
     */
    public function store()
    {
        $userId    = session()->get('userId');
        $productId = (int) $this->request->getPost('product_id');
        $slug      = $this->request->getPost('slug');

        // Validate input
        if (! $this->validate([
            'product_id' => 'required|is_natural_no_zero',
            'rating'     => 'required|integer|greater_than[0]|less_than_equal_to[5]',
            'comment'    => 'permit_empty|string|max_length[1000]',
        ])) {
            return redirect()->to('/produk/' . $slug)
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Pastikan produk ada
        $product = $this->productModel->find($productId);
        if (! $product) {
            return redirect()->to('/produk/' . $slug)->with('error', 'Produk tidak ditemukan.');
        }

        // Sudah pernah review produk ini?
        if ($this->reviewModel->hasReviewed($productId, $userId)) {
            return redirect()->to('/produk/' . $slug)
                ->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $this->reviewModel->insert([
            'product_id' => $productId,
            'user_id'    => $userId,
            'rating'     => $this->request->getPost('rating'),
            'comment'    => $this->request->getPost('comment'),
        ]);

        return redirect()->to('/produk/' . $slug)
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih! ⭐');
    }

    /**
     * POST /reviews/delete/{id}
     * Admin can delete any review
     */
    public function delete(int $id)
    {
        $review = $this->reviewModel->find($id);
        if (! $review) {
            return redirect()->back()->with('error', 'Ulasan tidak ditemukan.');
        }

        // Only admin or the reviewer can delete
        $role   = session()->get('role');
        $userId = session()->get('userId');
        if ($role !== 'admin' && $review['user_id'] !== $userId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $this->reviewModel->delete($id);
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
