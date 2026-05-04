<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table            = 'tags';
    protected $primaryKey        = 'id';
    protected $useAutoIncrement  = true;
    protected $returnType        = 'array';
    protected $useSoftDeletes    = false;
    protected $protectFields     = true;
    protected $allowedFields     = ['name', 'slug', 'description', 'color', 'icon'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    // Validation
    protected $validationRules      = [
        'name'        => 'required|string|max_length[50]|is_unique[tags.name]',
        'slug'        => 'required|string|max_length[50]|is_unique[tags.slug]',
        'description' => 'permit_empty|string',
        'color'       => 'required|regex_match[/^#[0-9A-F]{6}$/i]',
        'icon'        => 'permit_empty|string|max_length[50]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateSlug'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['generateSlug'];
    protected $afterUpdate    = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Generate slug from name
     */
    protected function generateSlug(array $data)
    {
        if (empty($data['data']['slug']) && !empty($data['data']['name'])) {
            $data['data']['slug'] = url_title($data['data']['name'], '-', true);
        }
        return $data;
    }

    /**
     * Get all tags with product count
     */
    public function getTagsWithCount()
    {
        return $this->select('tags.*, COUNT(product_tags.product_id) as product_count')
            ->join('product_tags', 'product_tags.tag_id = tags.id', 'left')
            ->groupBy('tags.id')
            ->findAll();
    }

    /**
     * Get tags for a specific product
     */
    public function getProductTags($productId)
    {
        return $this->select('tags.*')
            ->join('product_tags', 'product_tags.tag_id = tags.id')
            ->where('product_tags.product_id', $productId)
            ->findAll();
    }

    /**
     * Get products with a specific tag
     */
    public function getProductsByTag($tagId, $limit = null, $offset = 0)
    {
        $query = $this->select('products.*')
            ->join('product_tags', 'product_tags.product_id = products.id')
            ->where('product_tags.tag_id', $tagId)
            ->where('products.status', 'active');

        if ($limit) {
            $query = $query->limit($limit, $offset);
        }

        return $query->findAll();
    }

    /**
     * Attach tags to a product
     */
    public function attachTagsToProduct($productId, array $tagIds)
    {
        $db = \Config\Database::connect();

        // Delete existing tags
        $db->table('product_tags')->where('product_id', $productId)->delete();

        // Insert new tags
        if (!empty($tagIds)) {
            $data = [];
            foreach ($tagIds as $tagId) {
                $data[] = [
                    'product_id' => $productId,
                    'tag_id' => $tagId,
                ];
            }
            return $db->table('product_tags')->insertBatch($data);
        }

        return true;
    }

    /**
     * Search tags
     */
    public function searchTags($keyword, $limit = 10)
    {
        return $this->like('name', $keyword)
            ->orLike('description', $keyword)
            ->limit($limit)
            ->findAll();
    }
}
