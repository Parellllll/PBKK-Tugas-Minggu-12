<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CatalogController extends BaseController
{
    public function home(): string
    {
        $categoryModel = new CategoryModel();
        $productModel  = new ProductModel();

        $categories = $categoryModel->withProductCounts();
        $featured   = $productModel->select('products.*, categories.name AS category_name, categories.name_id AS category_name_id, categories.slug AS category_slug')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.status', 'active')
            ->orderBy('products.id', 'DESC')
            ->findAll(8);
        $featured = $productModel->attachPrimaryImages($featured);

        return view('storefront/home', [
            'title'      => se_t('ShopEase — Katalog Belanja & Manajemen Produk', 'ShopEase — Shopping Catalog & Product Management'),
            'categories' => $categories,
            'featured'   => $featured,
        ]);
    }

    public function index(): string
    {
        $categoryModel = new CategoryModel();
        $productModel  = new ProductModel();

        $q        = trim((string) $this->request->getGet('q'));
        $category = trim((string) $this->request->getGet('category'));
        $sort     = trim((string) $this->request->getGet('sort')) ?: 'newest';

        $productModel->select('products.*, categories.name AS category_name, categories.name_id AS category_name_id, categories.slug AS category_slug')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.status', 'active');

        if ($q !== '') {
            $productModel->groupStart()
                ->like('products.name', $q)
                ->orLike('products.name_id', $q)
                ->orLike('products.description', $q)
                ->groupEnd();
        }
        if ($category !== '') {
            $productModel->where('categories.slug', $category);
        }

        match ($sort) {
            'price_asc'  => $productModel->orderBy('products.price', 'ASC'),
            'price_desc' => $productModel->orderBy('products.price', 'DESC'),
            default      => $productModel->orderBy('products.id', 'DESC'),
        };

        $products = $productModel->paginate(8, 'catalog');
        $pager    = $productModel->pager;
        $products = $productModel->attachPrimaryImages($products);

        return view('storefront/catalog', [
            'title'      => se_t('Katalog Produk — ShopEase', 'Product Catalog — ShopEase'),
            'categories' => $categoryModel->withProductCounts(),
            'products'   => $products,
            'pager'      => $pager,
            'q'          => $q,
            'category'   => $category,
            'sort'       => $sort,
        ]);
    }

    public function detail(string $slug): string
    {
        $productModel = new ProductModel();
        $imageModel   = new ProductImageModel();

        $product = $productModel->select('products.*, categories.name AS category_name, categories.name_id AS category_name_id, categories.slug AS category_slug')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.slug', $slug)
            ->first();

        $isStaff = in_array(session()->get('role'), ['admin', 'operator'], true);
        if (!$product || ($product['status'] !== 'active' && !$isStaff)) {
            throw PageNotFoundException::forPageNotFound('Produk tidak ditemukan.');
        }

        $images = $imageModel->forProduct((int) $product['id']);

        $related = $productModel->select('products.*, categories.name AS category_name, categories.name_id AS category_name_id')
            ->join('categories', 'categories.id = products.category_id')
            ->where('products.status', 'active')
            ->where('products.category_id', $product['category_id'])
            ->where('products.id !=', $product['id'])
            ->orderBy('products.id', 'DESC')
            ->findAll(4);
        $related = $productModel->attachPrimaryImages($related);

        return view('storefront/detail', [
            'title'   => se_localized($product, 'name') . ' — ShopEase',
            'product' => $product,
            'images'  => $images,
            'related' => $related,
        ]);
    }
}

