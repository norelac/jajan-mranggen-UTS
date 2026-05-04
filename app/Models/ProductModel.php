<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'category_id', 'seller_id', 'name', 'slug',
        'description', 'price', 'stock', 'weight',
        'image', 'status', 'latitude', 'longitude',
        'address', 'geohash', 'average_rating', 'total_ratings',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[200]',
        'category_id' => 'required|integer',
        'price'       => 'required|decimal|greater_than[0]',
        'stock'       => 'required|integer|greater_than_equal_to[0]',
        'weight'      => 'required|integer|greater_than[0]',
        'status'      => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'name'        => ['required' => 'Nama produk wajib diisi'],
        'category_id' => ['required' => 'Kategori wajib dipilih'],
        'price'       => ['required' => 'Harga wajib diisi', 'greater_than' => 'Harga harus lebih dari 0'],
        'stock'       => ['required' => 'Stok wajib diisi', 'greater_than_equal_to' => 'Stok tidak boleh negatif'],
        'weight'      => ['required' => 'Berat wajib diisi', 'greater_than' => 'Berat harus lebih dari 0'],
    ];

    // ─── Custom Methods ──────────────────────────────────────

    public function getWithDetails()
    {
        return $this->select('products.*, categories.name as category_name, users.full_name as seller_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->join('users', 'users.id = products.seller_id')
                    ->where('products.deleted_at', null);
    }

    public function getBySeller(int $sellerId)
    {
        return $this->getWithDetails()
                    ->where('products.seller_id', $sellerId)
                    ->findAll();
    }

    public function getActiveProducts()
    {
        return $this->getWithDetails()
                    ->where('products.status', 'active')
                    ->findAll();
    }

    public function findBySlug(string $slug)
    {
        return $this->select('products.*, categories.name as category_name, users.full_name as seller_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->join('users', 'users.id = products.seller_id')
                    ->where('products.slug', $slug)
                    ->where('products.deleted_at', null)
                    ->first();
    }

    public function searchProducts(string $keyword, ?int $categoryId = null)
    {
        $builder = $this->getWithDetails()
                        ->where('products.status', 'active')
                        ->groupStart()
                            ->like('products.name', $keyword)
                            ->orLike('products.description', $keyword)
                        ->groupEnd();

        if ($categoryId) {
            $builder->where('products.category_id', $categoryId);
        }

        return $builder->findAll();
    }

    public function decreaseStock(int $productId, int $qty): bool
    {
        return $this->db->table('products')
                        ->where('id', $productId)
                        ->where('stock >=', $qty)
                        ->set('stock', "stock - {$qty}", false)
                        ->update();
    }

    public function increaseStock(int $productId, int $qty): bool
    {
        return $this->db->table('products')
                        ->where('id', $productId)
                        ->set('stock', "stock + {$qty}", false)
                        ->update();
    }

    // ─── Geocoding Methods ──────────────────────────────────

    /**
     * Get products near a location
     * @param float $latitude
     * @param float $longitude
     * @param float $radiusKm Distance in kilometers
     */
    public function getNearbyProducts(float $latitude, float $longitude, float $radiusKm = 5)
    {
        // Simple distance calculation using Haversine formula
        $radiusEarth = 6371; // km

        return $this->selectRaw(
            "products.*,
            categories.name as category_name,
            users.full_name as seller_name,
            ({$radiusEarth} * acos(
                cos(radians({$latitude})) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians({$longitude})) +
                sin(radians({$latitude})) *
                sin(radians(latitude))
            )) as distance"
        )
            ->join('categories', 'categories.id = products.category_id')
            ->join('users', 'users.id = products.seller_id')
            ->where('products.status', 'active')
            ->where('products.latitude IS NOT NULL')
            ->where('products.longitude IS NOT NULL')
            ->havingRaw("distance <= {$radiusKm}")
            ->orderBy('distance', 'ASC')
            ->findAll();
    }

    /**
     * Get products in a bounding box
     */
    public function getProductsInBounds($minLat, $minLng, $maxLat, $maxLng)
    {
        return $this->select('products.*, categories.name as category_name, users.full_name as seller_name')
            ->join('categories', 'categories.id = products.category_id')
            ->join('users', 'users.id = products.seller_id')
            ->where('products.status', 'active')
            ->where('products.latitude >=', $minLat)
            ->where('products.latitude <=', $maxLat)
            ->where('products.longitude >=', $minLng)
            ->where('products.longitude <=', $maxLng)
            ->findAll();
    }

    /**
     * Update product location via Nominatim geocoding
     */
    public function updateLocationFromGeocoding(int $productId, string $address): bool
    {
        $locationService = new \App\Services\GeocodeService();
        $coordinates = $locationService->getCoordinates($address);

        if ($coordinates) {
            return $this->update($productId, [
                'latitude' => $coordinates['latitude'],
                'longitude' => $coordinates['longitude'],
                'address' => $coordinates['address'],
                'geohash' => $coordinates['geohash'],
            ]);
        }

        return false;
    }

    // ─── Rating Methods ──────────────────────────────────

    /**
     * Get product with ratings
     */
    public function getWithRatings($productId)
    {
        $product = $this->select('products.*, categories.name as category_name, users.full_name as seller_name')
            ->join('categories', 'categories.id = products.category_id')
            ->join('users', 'users.id = products.seller_id')
            ->where('products.id', $productId)
            ->first();

        if ($product) {
            $ratingModel = new RatingModel();
            $product['ratings'] = $ratingModel->getProductRatings($productId, 5);
            $product['rating_stats'] = $ratingModel->getRatingStats($productId);
        }

        return $product;
    }

    // ─── Tag Methods ──────────────────────────────────

    /**
     * Get product with tags
     */
    public function getWithTags($productId)
    {
        $product = $this->find($productId);
        if ($product) {
            $tagModel = new TagModel();
            $product['tags'] = $tagModel->getProductTags($productId);
        }
        return $product;
    }

    /**
     * Get products filtered by tags
     */
    public function filterByTags(array $tagIds = [])
    {
        if (empty($tagIds)) {
            return $this->getActiveProducts();
        }

        return $this->select('DISTINCT products.*')
            ->join('product_tags', 'product_tags.product_id = products.id')
            ->join('categories', 'categories.id = products.category_id')
            ->join('users', 'users.id = products.seller_id')
            ->where('products.status', 'active')
            ->whereIn('product_tags.tag_id', $tagIds)
            ->findAll();
   }
}