<?= $this->extend('layouts/storefront') ?>

<?= $this->section('content') ?>
<section class="se-hero p-4 p-lg-5 mb-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="badge bg-white bg-opacity-25 rounded-pill px-3 py-2 mb-3">
                ShopEase · CodeIgniter 4.7.6 · <?= se_t('Katalog Dwibahasa & CRUD Terpadu', 'Bilingual Catalog & Unified CRUD') ?>
            </span>
            <h1 class="display-6 fw-bold mb-3">
                <?= se_t('Jelajahi Produk Pilihan & Kelola Inventaris Toko dalam Satu Aplikasi', 'Explore Curated Products & Manage Store Inventory in One Workspace') ?>
            </h1>
            <p class="opacity-75 mb-4">
                <?= se_t(
                    'Pengunjung dapat mencari, menyaring kategori, dan melihat galeri foto produk. Administrator dan operator mengelola kategori, harga, stok, ketersediaan, unggah multi-foto, serta direktori karyawan.',
                    'Shoppers can search, filter by category, and browse photo galleries. Administrators and operators manage categories, pricing, stock, availability, multi-photo uploads, and the employee directory.'
                ) ?>
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('catalog') ?>" class="btn btn-light fw-bold px-4 py-2 rounded-3" style="color:#5b21b6;"><?= se_t('Buka Katalog Belanja →', 'Browse Catalog →') ?></a>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-light fw-semibold px-4 py-2 rounded-3"><?= se_t('Masuk Staf / Pelanggan', 'Staff / Customer Sign In') ?></a>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1"><?= se_t('Kategori Belanja', 'Shop by Category') ?></h2>
            <p class="text-muted small mb-0"><?= se_t('Pilih kategori untuk menyaring katalog secara langsung.', 'Pick a category to filter the shopping catalog.') ?></p>
        </div>
    </div>
    <div class="row g-3">
        <?php foreach ($categories as $cat): ?>
            <div class="col-sm-6 col-lg-3">
                <a href="<?= base_url('catalog?category=' . urlencode($cat['slug'])) ?>" class="text-decoration-none text-dark">
                    <div class="se-surface p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h3 class="h6 fw-bold mb-0"><?= esc(se_localized($cat, 'name')) ?></h3>
                            <span class="badge bg-primary-subtle text-primary"><?= (int) $cat['product_count'] ?></span>
                        </div>
                        <p class="text-muted small mb-0"><?= esc($cat['description'] ?? '') ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1"><?= se_t('Produk Pilihan Terbaru', 'Featured New Arrivals') ?></h2>
            <p class="text-muted small mb-0"><?= se_t('Produk berstatus aktif yang siap dibeli dengan stok terpantau.', 'Active products with live stock availability.') ?></p>
        </div>
        <a href="<?= base_url('catalog') ?>" class="small fw-semibold text-decoration-none" style="color:var(--se-primary);"><?= se_t('Lihat Semua →', 'View All →') ?></a>
    </div>

    <div class="row g-4">
        <?php foreach ($featured as $p): ?>
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
                        <h3 class="h6 fw-bold mb-1"><?= esc(se_localized($p, 'name')) ?></h3>
                        <div class="fw-bold mb-3" style="color:var(--se-primary);">Rp <?= number_format((float) $p['price'], 0, ',', '.') ?></div>
                        <a href="<?= base_url('product/' . esc($p['slug'])) ?>" class="btn btn-se btn-sm mt-auto"><?= se_t('Detail Produk', 'View Details') ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>

