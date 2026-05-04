<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

class Home extends BaseController
{
    protected ProductModel  $productModel;
    protected CategoryModel $categoryModel;
    protected TagModel      $tagModel;
    protected ReviewModel   $reviewModel;
    protected UserModel     $userModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->tagModel      = new TagModel();
        $this->reviewModel   = new ReviewModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Jajan Mranggen - Jajanan Khas Mranggen',
            'products'   => $this->productModel->getActiveProducts(),
            'categories' => $this->categoryModel->withProductCount(),
            'allTags'    => $this->tagModel->getTagsWithCount(),
        ];
        return view('home/index', $data);
    }

    public function products()
    {
        $keyword    = $this->request->getGet('q');
        $categoryId = $this->request->getGet('category');
        $tagSlug    = $this->request->getGet('tag');

        if ($tagSlug) {
            // Filter by tag slug
            $tagProductIds = $this->tagModel->getProductIdsBySlug($tagSlug);
            if (empty($tagProductIds)) {
                $products = [];
            } else {
                $products = $this->productModel->getWithDetails()
                    ->where('products.status', 'active')
                    ->whereIn('products.id', $tagProductIds)
                    ->findAll();
            }
        } elseif ($keyword) {
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
            'allTags'    => $this->tagModel->getTagsWithCount(),
            'keyword'    => $keyword,
            'categoryId' => $categoryId,
            'activeTag'  => $tagSlug,
        ];
        return view('home/products', $data);
    }

    public function productDetail(string $slug)
    {
        $product = $this->productModel->findBySlug($slug);
        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Produk tidak ditemukan');
        }

        // Get seller info (with coordinates)
        $seller = $this->userModel->find($product['seller_id']);

        // Get reviews & rating
        $reviews = $this->reviewModel->getProductReviews($product['id']);
        $rating  = $this->reviewModel->getAverageRating($product['id']);

        // Get tags for this product
        $tags = $this->tagModel->getProductTags($product['id']);

        // Check review eligibility for any logged-in user
        $canReview   = false;
        $hasReviewed = false;
        $userId = session()->get('userId');
        if (session()->get('isLoggedIn') && $userId) {
            $canReview   = true; // semua user login bisa review
            $hasReviewed = $this->reviewModel->hasReviewed($product['id'], $userId);
        }

        // Related products (same category, exclude current)
        $related = $this->productModel->getWithDetails()
            ->where('products.category_id', $product['category_id'])
            ->where('products.id !=', $product['id'])
            ->where('products.status', 'active')
            ->limit(4)
            ->findAll();

        $data = [
            'title'       => $product['name'] . ' - Jajan Mranggen',
            'product'     => $product,
            'seller'      => $seller,
            'reviews'     => $reviews,
            'rating'      => $rating,
            'tags'        => $tags,
            'canReview'   => $canReview,
            'hasReviewed' => $hasReviewed,
            'related'     => $related,
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
            'allTags'  => $this->tagModel->getTagsWithCount(),
        ];
        return view('home/category', $data);
    }

    public function tag(string $slug)
    {
        $tag = $this->tagModel->where('slug', $slug)->first();
        if (! $tag) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tag tidak ditemukan');
        }

        $productIds = $this->tagModel->getProductIdsBySlug($slug);
        $products   = empty($productIds)
            ? []
            : $this->productModel->getWithDetails()
                ->where('products.status', 'active')
                ->whereIn('products.id', $productIds)
                ->findAll();

        $data = [
            'title'    => '#' . $tag['name'] . ' - Jajan Mranggen',
            'tag'      => $tag,
            'products' => $products,
            'allTags'  => $this->tagModel->getTagsWithCount(),
        ];
        return view('home/products', $data);
    }

    /**
     * API: Save geocoded coordinates for a seller user
     * Called from Leaflet map JS (fire & forget)
     */
    public function saveGeocode()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $body   = $this->request->getJSON(true);
        $userId = (int) ($body['user_id'] ?? 0);
        $lat    = (float) ($body['lat'] ?? 0);
        $lng    = (float) ($body['lng'] ?? 0);

        if ($userId && $lat && $lng) {
            $this->userModel->update($userId, [
                'latitude'  => $lat,
                'longitude' => $lng,
            ]);
        }

        return $this->response->setJSON(['ok' => true]);
    }
}
