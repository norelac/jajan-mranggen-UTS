<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table            = 'ratings';
    protected $primaryKey        = 'id';
    protected $useAutoIncrement  = true;
    protected $returnType        = 'array';
    protected $useSoftDeletes    = false;
    protected $protectFields     = true;
    protected $allowedFields     = ['product_id', 'user_id', 'rating', 'review', 'helpful_count'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'product_id' => 'required|integer',
        'user_id'    => 'required|integer',
        'rating'     => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'review'     => 'permit_empty|string|max_length[1000]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = ['updateProductRating'];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = ['updateProductRating'];
    protected $beforeDelete   = [];
    protected $afterDelete    = ['updateProductRating'];

    /**
     * Get ratings for a specific product with user info
     */
    public function getProductRatings($productId, $limit = 10, $offset = 0)
    {
        return $this->select('ratings.*, users.fullName, users.email')
            ->join('users', 'users.id = ratings.user_id')
            ->where('ratings.product_id', $productId)
            ->orderBy('ratings.created_at', 'DESC')
            ->limit($limit, $offset)
            ->find();
    }

    /**
     * Get average rating for a product
     */
    public function getAverageRating($productId)
    {
        $result = $this->selectAvg('rating')
            ->where('product_id', $productId)
            ->first();

        return round($result['rating'] ?? 0, 1);
    }

    /**
     * Get rating stats for a product
     */
    public function getRatingStats($productId)
    {
        $stats = [
            'average' => 0,
            'total' => 0,
            'distribution' => [],
        ];

        $result = $this->where('product_id', $productId)->first();
        if (!$result) {
            return $stats;
        }

        $total = $this->where('product_id', $productId)->countAllResults();
        $stats['total'] = $total;

        if ($total > 0) {
            $stats['average'] = $this->selectAvg('rating')
                ->where('product_id', $productId)
                ->first()['rating'] ?? 0;

            // Get distribution
            for ($i = 1; $i <= 5; $i++) {
                $count = $this->where('product_id', $productId)
                    ->where('rating', $i)
                    ->countAllResults();
                $stats['distribution'][$i] = [
                    'count' => $count,
                    'percentage' => round(($count / $total) * 100),
                ];
            }
        }

        return $stats;
    }

    /**
     * Callback to update product rating stats
     */
    protected function updateProductRating(array $data)
    {
        $productId = $data['data']['product_id'] ?? null;
        if ($productId) {
            $productModel = new ProductModel();
            $avgRating = $this->getAverageRating($productId);
            $totalRatings = $this->where('product_id', $productId)->countAllResults();

            $productModel->update($productId, [
                'average_rating' => $avgRating,
                'total_ratings' => $totalRatings,
            ]);
        }

        return $data;
    }
}
