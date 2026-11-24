<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'category_id',
        'name',
        'name_id',
        'slug',
        'description',
        'price',
        'stock',
        'status',
    ];
    protected $useTimestamps    = true;

    public function attachPrimaryImages(array $products): array
    {
        if (empty($products)) {
            return [];
        }

        $ids = array_column($products, 'id');
        $imgModel = new ProductImageModel();
        $images = $imgModel->whereIn('product_id', $ids)
            ->orderBy('is_primary', 'DESC')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $byProduct = [];
        foreach ($images as $img) {
            $pid = (int) $img['product_id'];
            if (!isset($byProduct[$pid])) {
                $byProduct[$pid] = ['primary' => $img['filename'], 'count' => 0];
            }
            $byProduct[$pid]['count']++;
        }

        foreach ($products as &$p) {
            $pid = (int) $p['id'];
            $p['primary_image'] = $byProduct[$pid]['primary'] ?? null;
            $p['image_count']   = $byProduct[$pid]['count'] ?? 0;
        }

        return $products;
    }

    public function inventorySummary(): array
    {
        return [
            'total'       => (int) $this->db->table('products')->countAllResults(),
            'active'      => (int) $this->db->table('products')->where('status', 'active')->countAllResults(),
            'inactive'    => (int) $this->db->table('products')->where('status', 'inactive')->countAllResults(),
            'low_stock'   => (int) $this->db->table('products')->where('stock >=', 1)->where('stock <=', 5)->countAllResults(),
            'out_of_stock'=> (int) $this->db->table('products')->where('stock', 0)->countAllResults(),
        ];
    }
}

