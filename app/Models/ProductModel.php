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
        'image', 'status',
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
}
