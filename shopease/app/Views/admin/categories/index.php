<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1"><?= se_t('Manajemen Kategori Produk', 'Product Category Management') ?></h1>
        <p class="text-muted small mb-0"><?= se_t('Kategori dapat dihapus apabila seluruh produk di dalamnya telah dipindahkan atau dihapus.', 'Categories can be deleted once all assigned products are moved or removed.') ?></p>
    </div>
    <a href="<?= base_url("{$prefix}/categories/create") ?>" class="btn btn-se px-3 py-2">+ <?= se_t('Tambah Kategori', 'Add Category') ?></a>
</div>

<div class="row g-3">
    <?php foreach ($categories as $cat): ?>
        <div class="col-md-6 col-xl-4">
            <div class="se-surface p-4 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div>
                        <h2 class="h6 fw-bold mb-0"><?= esc($cat['name']) ?></h2>
                        <?php if (!empty($cat['name_id'])): ?>
                            <div class="text-muted small">ID: <?= esc($cat['name_id']) ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-primary-subtle text-primary"><?= (int) $cat['product_count'] ?> <?= se_t('produk', 'items') ?></span>
                </div>
                <div class="small text-muted mb-2">Slug: <code><?= esc($cat['slug']) ?></code></div>
                <p class="small text-muted flex-grow-1 mb-3"><?= esc($cat['description'] ?: '-') ?></p>
                <div class="d-flex gap-2 mt-auto">
                    <a href="<?= base_url("{$prefix}/categories/{$cat['id']}/edit") ?>" class="btn btn-outline-primary btn-sm rounded-3 flex-fill"><?= se_t('Edit', 'Edit') ?></a>
                    <form method="post" action="<?= base_url("{$prefix}/categories/{$cat['id']}/delete") ?>" class="flex-fill" onsubmit="return confirm('Hapus kategori ini?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 w-100"><?= se_t('Hapus', 'Delete') ?></button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>

