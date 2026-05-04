<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table      = 'reviews';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['product_id', 'user_id', 'rating', 'comment'];

    // Get reviews for a product with reviewer info
    public function getProductReviews(int $productId): array
    {
        return $this->db->table('reviews r')
            ->select('r.*, u.full_name, u.avatar')
            ->join('users u', 'u.id = r.user_id')
            ->where('r.product_id', $productId)
            ->orderBy('r.created_at', 'DESC')
            ->get()->getResultArray();
    }

    // Get average rating for a product
    public function getAverageRating(int $productId): array
    {
        $row = $this->db->table('reviews')
            ->select('AVG(rating) as avg_rating, COUNT(id) as total_reviews')
            ->where('product_id', $productId)
            ->get()->getRowArray();
        return [
            'avg'   => round($row['avg_rating'] ?? 0, 1),
            'total' => (int) ($row['total_reviews'] ?? 0),
        ];
    }

    // Check if user already reviewed this product
    public function hasReviewed(int $productId, int $userId): bool
    {
        return $this->where('product_id', $productId)
            ->where('user_id', $userId)
            ->countAllResults() > 0;
    }

    // Check if user is eligible to review (must have bought the product)
    public function canReview(int $productId, int $userId): bool
    {
        $bought = $this->db->table('order_items oi')
            ->join('orders o', 'o.id = oi.order_id')
            ->where('oi.product_id', $productId)
            ->where('o.user_id', $userId)
            ->where('o.status', 'delivered')
            ->countAllResults();
        return $bought > 0;
    }
}
