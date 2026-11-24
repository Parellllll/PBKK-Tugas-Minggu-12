<?= $this->extend('layouts/storefront') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1"><?= se_t('Katalog Belanja ShopEase', 'ShopEase Product Catalog') ?></h1>
        <p class="text-muted small mb-0"><?= se_t('Hanya produk berstatus Tersedia (active) yang tampil pada katalog pelanggan.', 'Only active products are displayed in the customer catalog.') ?></p>
    </div>
</div>

<form method="get" action="<?= base_url('catalog') ?>" class="se-surface p-3 mb-4">
    <div class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" class="form-control form-control-sm" name="q" value="<?= esc($q) ?>" placeholder="<?= se_t('Cari nama atau deskripsi produk...', 'Search product name or description...') ?>">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select form-select-sm">
                <option value=""><?= se_t('Semua Kategori', 'All Categories') ?></option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc($cat['slug']) ?>" <?= $category === $cat['slug'] ? 'selected' : '' ?>>
                        <?= esc(se_localized($cat, 'name')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select form-select-sm">
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>><?= se_t('Terbaru', 'Newest') ?></option>
                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>><?= se_t('Harga Terendah', 'Price: Low-High') ?></option>
                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>><?= se_t('Harga Tertinggi', 'Price: High-Low') ?></option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-se btn-sm flex-fill"><?= se_t('Terapkan', 'Apply') ?></button>
            <a href="<?= base_url('catalog') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </div>
</form>

<?php if (empty($products)): ?>
    <div class="se-surface p-5 text-center text-muted">
        <?= se_t('Tidak ada produk yang cocok dengan pencarian atau filter Anda.', 'No products match your search or filter.') ?>
    </div>
<?php else: ?>
    <div class="row g-4 mb-4">
        <?php foreach ($products as $p): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="se-surface h-100 d-flex flex-column overflow-hidden">
                    <img class="product-thumb"
                         src="<?= $p['primary_image'] ? base_url('uploads/products/' . esc($p['primary_image'])) : '' ?>"
                         alt="<?= esc(se_localized($p, 'name')) ?>">
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge bg-primary-subtle text-primary" style="font-size:11px;"><?= esc(se_lang() === 'id' && $p['category_name_id'] ? $p['category_name_id'] : $p['category_name']) ?></span>
                            <?php if ((int) $p['stock'] === 0): ?>
                                <span class="badge bg-danger-subtle text-danger" style="font-size:11px;"><?= se_t('Stok Habis', 'Out of Stock') ?></span>
                            <?php elseif ((int) $p['stock'] <= 5): ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size:11px;"><?= se_t('Menipis: ', 'Low: ') . (int) $p['stock'] ?></span>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:11px;"><?= se_t('Stok ', 'Stock ') . (int) $p['stock'] ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="h6 fw-bold mb-1"><?= esc(se_localized($p, 'name')) ?></h2>
                        <div class="fw-bold mb-2" style="color:var(--se-primary);">Rp <?= number_format((float) $p['price'], 0, ',', '.') ?></div>
                        <p class="text-muted small mb-3 flex-grow-1"><?= esc(mb_strimwidth((string) ($p['description'] ?? ''), 0, 75, '...')) ?></p>
                        <a href="<?= base_url('product/' . esc($p['slug'])) ?>" class="btn btn-se btn-sm mt-auto"><?= se_t('Lihat Detail', 'View Details') ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-center">
        <?= $pager->links('catalog') ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>

