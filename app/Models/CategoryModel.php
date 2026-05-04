<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = ['name', 'slug', 'description', 'image'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'slug' => 'required|is_unique[categories.slug,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'  => 'Nama kategori wajib diisi',
            'min_length' => 'Nama minimal 3 karakter',
        ],
        'slug' => [
            'required'  => 'Slug wajib diisi',
            'is_unique' => 'Slug sudah digunakan',
        ],
    ];

    // ─── Custom Methods ──────────────────────────────────────

    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function withProductCount()
    {
        return $this->select('categories.*, COUNT(products.id) as product_count')
                    ->join('products', 'products.category_id = categories.id AND products.deleted_at IS NULL', 'left')
                    ->groupBy('categories.id')
                    ->findAll();
    }
}
