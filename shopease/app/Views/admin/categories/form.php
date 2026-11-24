<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<?php $isEdit = !empty($category); ?>
<div class="mb-3">
    <a href="<?= base_url("{$prefix}/categories") ?>" class="small fw-semibold text-decoration-none">&larr; <?= se_t('Kembali ke Daftar Kategori', 'Back to Categories') ?></a>
</div>

<div class="se-surface p-4 p-md-5" style="max-width:700px;">
    <h1 class="h4 fw-bold mb-1"><?= $isEdit ? se_t('Edit Kategori', 'Edit Category') : se_t('Tambah Kategori Baru', 'Create New Category') ?></h1>
    <p class="text-muted small mb-4"><?= se_t('Nama utama wajib diisi; nama bahasa Indonesia bersifat opsional untuk katalog dwibahasa.', 'Primary name is required; Indonesian translation is optional.') ?></p>

    <form method="post" action="<?= $isEdit ? base_url("{$prefix}/categories/{$category['id']}/update") : base_url("{$prefix}/categories/store") ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label small fw-semibold" for="name"><?= se_t('Nama Kategori Utama (EN / Default)', 'Primary Category Name') ?> *</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name', $category['name'] ?? '')) ?>" required placeholder="Contoh: Smart Gadgets">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold" for="name_id"><?= se_t('Nama Bahasa Indonesia (Opsional)', 'Indonesian Name (Optional)') ?></label>
            <input type="text" class="form-control" id="name_id" name="name_id" value="<?= esc(old('name_id', $category['name_id'] ?? '')) ?>" placeholder="Contoh: Gawai Pintar">
        </div>
        <div class="mb-4">
            <label class="form-label small fw-semibold" for="description"><?= se_t('Deskripsi', 'Description') ?></label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Deskripsi singkat kelompok produk..."><?= esc(old('description', $category['description'] ?? '')) ?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-se px-4"><?= $isEdit ? se_t('Simpan Perubahan', 'Update Category') : se_t('Simpan Kategori', 'Create Category') ?></button>
            <a href="<?= base_url("{$prefix}/categories") ?>" class="btn btn-outline-secondary rounded-3"><?= se_t('Batal', 'Cancel') ?></a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

