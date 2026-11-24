<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;

class ProductController extends BaseController
{
    public const MAX_IMAGES_PER_PRODUCT = 6;
    public const MAX_IMAGE_BYTES        = 2097152; // 2 MiB
    public const MAX_DIMENSION          = 4096;
    public const MAX_TOTAL_PIXELS       = 8000000;

    public function index(): string
    {
        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();

        $q          = trim((string) $this->request->getGet('q'));
        $categoryId = (int) $this->request->getGet('category_id');
        $status     = trim((string) $this->request->getGet('status'));
        $stockState = trim((string) $this->request->getGet('stock'));

        $summary = $productModel->inventorySummary();

        $productModel->select('products.*, categories.name AS category_name, categories.name_id AS category_name_id')
            ->join('categories', 'categories.id = products.category_id');

        if ($q !== '') {
            $productModel->groupStart()
                ->like('products.name', $q)
                ->orLike('products.name_id', $q)
                ->orLike('products.slug', $q)
                ->groupEnd();
        }
        if ($categoryId > 0) {
            $productModel->where('products.category_id', $categoryId);
        }
        if (in_array($status, ['active', 'inactive'], true)) {
            $productModel->where('products.status', $status);
        }
        if ($stockState === 'low') {
            $productModel->where('products.stock >=', 1)->where('products.stock <=', 5);
        } elseif ($stockState === 'out') {
            $productModel->where('products.stock', 0);
        } elseif ($stockState === 'in') {
            $productModel->where('products.stock >', 5);
        }

        $products = $productModel->orderBy('products.id', 'DESC')->paginate(8, 'products');
        $pager    = $productModel->pager;
        $products = $productModel->attachPrimaryImages($products);

        return view('admin/products/index', [
            'title'      => se_t('Inventaris Produk — ShopEase', 'Product Inventory — ShopEase'),
            'activeNav'  => 'products',
            'prefix'     => se_workspace_prefix(),
            'summary'    => $summary,
            'categories' => $categoryModel->orderBy('name', 'ASC')->findAll(),
            'products'   => $products,
            'pager'      => $pager,
            'q'          => $q,
            'categoryId' => $categoryId,
            'status'     => $status,
            'stockState' => $stockState,
        ]);
    }

    public function create(): string
    {
        return view('admin/products/form', [
            'title'      => se_t('Tambah Produk — ShopEase', 'Add Product — ShopEase'),
            'activeNav'  => 'products',
            'prefix'     => se_workspace_prefix(),
            'categories' => (new CategoryModel())->orderBy('name', 'ASC')->findAll(),
            'product'    => null,
            'images'     => [],
        ]);
    }

    public function store()
    {
        $prefix = se_workspace_prefix();
        $rules  = [
            'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
            'name'        => 'required|min_length[3]|max_length[160]',
            'name_id'     => 'permit_empty|max_length[160]',
            'price'       => 'required|numeric|greater_than_equal_to[0]',
            'stock'       => 'required|is_natural',
            'status'      => 'required|in_list[active,inactive]',
            'description' => 'permit_empty|max_length[3000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productModel = new ProductModel();
        $name         = trim((string) $this->request->getPost('name'));
        $slug         = $this->uniqueSlug($productModel, $name);

        $productId = (int) $productModel->insert([
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => $name,
            'name_id'     => trim((string) $this->request->getPost('name_id')) ?: null,
            'slug'        => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'status'      => (string) $this->request->getPost('status'),
        ], true);

        // Proses upload foto opsional saat tambah produk
        $uploadRes = $this->handleUploadedImages($productId);
        if (!$uploadRes['ok']) {
            return redirect()->to("/{$prefix}/products/{$productId}/edit")
                ->with('warning', 'Produk tersimpan, namun sebagian foto ditolak: ' . $uploadRes['error']);
        }

        return redirect()->to("/{$prefix}/products/{$productId}/edit")
            ->with('success', se_t('Produk baru berhasil disimpan! Anda dapat mengelola galeri fotonya di halaman ini.', 'Product created! You can manage its photo gallery here.'));
    }

    public function edit(int $id): string
    {
        $product = (new ProductModel())->find($id);
        if (!$product) {
            throw PageNotFoundException::forPageNotFound("Produk #{$id} tidak ditemukan.");
        }

        return view('admin/products/form', [
            'title'      => se_t('Edit Produk — ShopEase', 'Edit Product — ShopEase'),
            'activeNav'  => 'products',
            'prefix'     => se_workspace_prefix(),
            'categories' => (new CategoryModel())->orderBy('name', 'ASC')->findAll(),
            'product'    => $product,
            'images'     => (new ProductImageModel())->forProduct($id),
        ]);
    }

    public function update(int $id)
    {
        $prefix       = se_workspace_prefix();
        $productModel = new ProductModel();
        $product      = $productModel->find($id);
        if (!$product) {
            throw PageNotFoundException::forPageNotFound("Produk #{$id} tidak ditemukan.");
        }

        $rules = [
            'category_id' => 'required|is_natural_no_zero|is_not_unique[categories.id]',
            'name'        => 'required|min_length[3]|max_length[160]',
            'name_id'     => 'permit_empty|max_length[160]',
            'price'       => 'required|numeric|greater_than_equal_to[0]',
            'stock'       => 'required|is_natural',
            'status'      => 'required|in_list[active,inactive]',
            'description' => 'permit_empty|max_length[3000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->uniqueSlug($productModel, $name, $id);

        $productModel->update($id, [
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => $name,
            'name_id'     => trim((string) $this->request->getPost('name_id')) ?: null,
            'slug'        => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'status'      => (string) $this->request->getPost('status'),
        ]);

        return redirect()->to("/{$prefix}/products/{$id}/edit")
            ->with('success', se_t('Perubahan produk berhasil disimpan.', 'Product updated successfully.'));
    }

    public function uploadImages(int $id)
    {
        $prefix  = se_workspace_prefix();
        $product = (new ProductModel())->find($id);
        if (!$product) {
            throw PageNotFoundException::forPageNotFound("Produk #{$id} tidak ditemukan.");
        }

        $res = $this->handleUploadedImages($id);
        if (!$res['ok']) {
            return redirect()->to("/{$prefix}/products/{$id}/edit")->with('error', $res['error']);
        }
        if ($res['count'] === 0) {
            return redirect()->to("/{$prefix}/products/{$id}/edit")->with('warning', se_t('Pilih minimal satu file gambar (JPEG, PNG, atau WebP).', 'Please select at least one image file.'));
        }

        return redirect()->to("/{$prefix}/products/{$id}/edit")
            ->with('success', se_t("{$res['count']} foto produk berhasil diunggah.", "{$res['count']} product image(s) uploaded."));
    }

    public function setPrimaryImage(int $productId, int $imageId)
    {
        $prefix   = se_workspace_prefix();
        $imgModel = new ProductImageModel();
        $img      = $imgModel->where('id', $imageId)->where('product_id', $productId)->first();

        if (!$img) {
            return redirect()->to("/{$prefix}/products/{$productId}/edit")->with('error', 'Foto tidak ditemukan.');
        }

        $imgModel->where('product_id', $productId)->set(['is_primary' => 0])->update();
        $imgModel->update($imageId, ['is_primary' => 1]);

        return redirect()->to("/{$prefix}/products/{$productId}/edit")
            ->with('success', se_t('Gambar utama produk berhasil diperbarui.', 'Primary product image updated.'));
    }

    public function deleteImage(int $productId, int $imageId)
    {
        $prefix   = se_workspace_prefix();
        $imgModel = new ProductImageModel();
        $img      = $imgModel->where('id', $imageId)->where('product_id', $productId)->first();

        if (!$img) {
            return redirect()->to("/{$prefix}/products/{$productId}/edit")->with('error', 'Foto tidak ditemukan.');
        }

        $wasPrimary = (int) $img['is_primary'] === 1;
        $fullPath   = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . $img['filename'];
        if (is_file($fullPath) && !str_starts_with($img['filename'], 'demo_')) {
            @unlink($fullPath);
        }

        $imgModel->delete($imageId);

        if ($wasPrimary) {
            $next = $imgModel->where('product_id', $productId)->orderBy('id', 'ASC')->first();
            if ($next) {
                $imgModel->update($next['id'], ['is_primary' => 1]);
            }
        }

        return redirect()->to("/{$prefix}/products/{$productId}/edit")
            ->with('success', se_t('Foto produk berhasil dihapus.', 'Product photo deleted.'));
    }

    public function delete(int $id)
    {
        $prefix       = se_workspace_prefix();
        $productModel = new ProductModel();
        $imgModel     = new ProductImageModel();

        $product = $productModel->find($id);
        if (!$product) {
            return redirect()->to("/{$prefix}/products")->with('error', 'Produk tidak ditemukan.');
        }

        $images = $imgModel->forProduct($id);
        foreach ($images as $img) {
            $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . $img['filename'];
            if (is_file($path) && !str_starts_with($img['filename'], 'demo_')) {
                @unlink($path);
            }
        }

        $productModel->delete($id);

        return redirect()->to("/{$prefix}/products")
            ->with('success', se_t("Produk \"{$product['name']}\" berhasil dihapus.", "Product \"{$product['name']}\" deleted."));
    }

    /**
     * @return array{ok: bool, count: int, error?: string}
     */
    private function handleUploadedImages(int $productId): array
    {
        $files = $this->request->getFileMultiple('images');
        if (empty($files)) {
            $single = $this->request->getFile('image');
            $files  = $single ? [$single] : [];
        }

        $validFiles = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                $validFiles[] = $file;
            }
        }

        if (empty($validFiles)) {
            return ['ok' => true, 'count' => 0];
        }

        $imgModel      = new ProductImageModel();
        $existingCount = $imgModel->where('product_id', $productId)->countAllResults();

        if ($existingCount + count($validFiles) > self::MAX_IMAGES_PER_PRODUCT) {
            return [
                'ok'    => false,
                'count' => 0,
                'error' => 'Maksimal ' . self::MAX_IMAGES_PER_PRODUCT . ' foto per produk (saat ini sudah ada ' . $existingCount . ').',
            ];
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];
        $uploadDir    = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $hasPrimary = $imgModel->where('product_id', $productId)->where('is_primary', 1)->countAllResults() > 0;
        $saved      = 0;

        foreach ($validFiles as $file) {
            if (!$file->isValid() || $file->hasMoved()) {
                return ['ok' => false, 'count' => $saved, 'error' => 'File gagal diunggah: ' . $file->getErrorString()];
            }
            if ($file->getSize() > self::MAX_IMAGE_BYTES) {
                return ['ok' => false, 'count' => $saved, 'error' => 'Ukuran foto melebihi batas maksimal 2 MiB.'];
            }

            $ext  = strtolower($file->getClientExtension());
            $mime = $file->getMimeType();
            if (!in_array($ext, $allowedExts, true) || !in_array($mime, $allowedMimes, true)) {
                return ['ok' => false, 'count' => $saved, 'error' => "Format file tidak diizinkan ({$mime}). Hanya JPEG, PNG, dan WebP yang diperbolehkan."];
            }

            $dim = @getimagesize($file->getTempName());
            if ($dim === false || $dim[0] > self::MAX_DIMENSION || $dim[1] > self::MAX_DIMENSION || ($dim[0] * $dim[1]) > self::MAX_TOTAL_PIXELS) {
                return ['ok' => false, 'count' => $saved, 'error' => 'File bukan gambar valid atau resolusi melebihi 4096px / 8 megapiksel.'];
            }

            $randomName = $file->getRandomName();
            $file->move($uploadDir, $randomName);

            $imgModel->insert([
                'product_id' => $productId,
                'filename'   => $randomName,
                'is_primary' => $hasPrimary ? 0 : 1,
                'sort_order' => $existingCount + $saved + 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $hasPrimary = true;
            $saved++;
        }

        return ['ok' => true, 'count' => $saved];
    }

    private function uniqueSlug(ProductModel $model, string $name, ?int $ignoreId = null): string
    {
        $base = url_title($name, '-', true) ?: 'product';
        $slug = $base;
        $i    = 2;
        while (true) {
            $q = $model->where('slug', $slug);
            if ($ignoreId !== null) {
                $q->where('id !=', $ignoreId);
            }
            if ($q->countAllResults() === 0) {
                return $slug;
            }
            $slug = $base . '-' . $i++;
        }
    }
}

