<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CategoryController extends BaseController
{
    public function index(): string
    {
        $model = new CategoryModel();
        $prefix = se_workspace_prefix();

        return view('admin/categories/index', [
            'title'      => se_t('Manajemen Kategori — ShopEase', 'Category Management — ShopEase'),
            'activeNav'  => 'categories',
            'prefix'     => $prefix,
            'categories' => $model->withProductCounts(),
        ]);
    }

    public function create(): string
    {
        return view('admin/categories/form', [
            'title'     => se_t('Tambah Kategori — ShopEase', 'Add Category — ShopEase'),
            'activeNav' => 'categories',
            'prefix'    => se_workspace_prefix(),
            'category'  => null,
        ]);
    }

    public function store()
    {
        $prefix = se_workspace_prefix();
        $rules  = [
            'name'        => 'required|min_length[3]|max_length[120]',
            'name_id'     => 'permit_empty|max_length[120]',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new CategoryModel();
        $name  = trim((string) $this->request->getPost('name'));
        $slug  = $this->uniqueSlug($model, $name);

        $model->insert([
            'name'        => $name,
            'name_id'     => trim((string) $this->request->getPost('name_id')) ?: null,
            'slug'        => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'is_active'   => 1,
        ]);

        return redirect()->to("/{$prefix}/categories")
            ->with('success', se_t('Kategori baru berhasil ditambahkan.', 'Category created successfully.'));
    }

    public function edit(int $id): string
    {
        $model    = new CategoryModel();
        $category = $model->find($id);
        if (!$category) {
            throw PageNotFoundException::forPageNotFound("Kategori #{$id} tidak ditemukan.");
        }

        return view('admin/categories/form', [
            'title'     => se_t('Edit Kategori — ShopEase', 'Edit Category — ShopEase'),
            'activeNav' => 'categories',
            'prefix'    => se_workspace_prefix(),
            'category'  => $category,
        ]);
    }

    public function update(int $id)
    {
        $prefix = se_workspace_prefix();
        $model  = new CategoryModel();
        $cat    = $model->find($id);
        if (!$cat) {
            throw PageNotFoundException::forPageNotFound("Kategori #{$id} tidak ditemukan.");
        }

        $rules = [
            'name'        => 'required|min_length[3]|max_length[120]',
            'name_id'     => 'permit_empty|max_length[120]',
            'description' => 'permit_empty|max_length[1000]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->uniqueSlug($model, $name, $id);

        $model->update($id, [
            'name'        => $name,
            'name_id'     => trim((string) $this->request->getPost('name_id')) ?: null,
            'slug'        => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
        ]);

        return redirect()->to("/{$prefix}/categories")
            ->with('success', se_t('Kategori berhasil diperbarui.', 'Category updated successfully.'));
    }

    public function delete(int $id)
    {
        $prefix   = se_workspace_prefix();
        $catModel = new CategoryModel();
        $category = $catModel->find($id);
        if (!$category) {
            return redirect()->to("/{$prefix}/categories")->with('error', 'Kategori tidak ditemukan.');
        }

        $productCount = (new ProductModel())->where('category_id', $id)->countAllResults();
        if ($productCount > 0) {
            return redirect()->to("/{$prefix}/categories")
                ->with('error', se_t(
                    "Kategori \"{$category['name']}\" tidak dapat dihapus karena masih memiliki {$productCount} produk. Pindahkan atau hapus produknya terlebih dahulu.",
                    "Category \"{$category['name']}\" cannot be deleted because it still has {$productCount} products."
                ));
        }

        $catModel->delete($id);

        return redirect()->to("/{$prefix}/categories")
            ->with('success', se_t('Kategori berhasil dihapus.', 'Category deleted successfully.'));
    }

    private function uniqueSlug(CategoryModel $model, string $name, ?int $ignoreId = null): string
    {
        $base = url_title($name, '-', true) ?: 'category';
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

