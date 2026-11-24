<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1"><?= se_t('Inventaris Produk', 'Product Inventory') ?></h1>
        <p class="text-muted small mb-0"><?= se_t('Ringkasan katalog, kondisi stok, harga, ketersediaan, serta pengelolaan galeri foto.', 'Catalog overview, stock health, pricing, availability, and photo gallery management.') ?></p>
    </div>
    <a href="<?= base_url("{$prefix}/products/create") ?>" class="btn btn-se px-3 py-2">+ <?= se_t('Tambah Produk', 'Add Product') ?></a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="se-surface p-3">
            <div class="small text-muted"><?= se_t('Total Produk', 'Total Products') ?></div>
            <div class="fs-3 fw-bold"><?= (int) $summary['total'] ?></div>
            <div class="small text-muted"><?= (int) $summary['active'] ?> <?= se_t('tersedia', 'active') ?> · <?= (int) $summary['inactive'] ?> <?= se_t('nonaktif', 'hidden') ?></div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="se-surface p-3">
            <div class="small text-muted"><?= se_t('Tersedia di Katalog', 'Published') ?></div>
            <div class="fs-3 fw-bold text-success"><?= (int) $summary['active'] ?></div>
            <div class="small text-muted"><?= se_t('Tampil untuk pelanggan', 'Visible to shoppers') ?></div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="se-surface p-3">
            <div class="small text-muted"><?= se_t('Stok Menipis (1–5)', 'Low Stock (1–5)') ?></div>
            <div class="fs-3 fw-bold text-warning"><?= (int) $summary['low_stock'] ?></div>
            <div class="small text-muted"><?= se_t('Perlu segera restock', 'Needs restocking') ?></div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="se-surface p-3">
            <div class="small text-muted"><?= se_t('Stok Habis (0)', 'Out of Stock (0)') ?></div>
            <div class="fs-3 fw-bold text-danger"><?= (int) $summary['out_of_stock'] ?></div>
            <div class="small text-muted"><?= se_t('Label stok habis', 'Marked out of stock') ?></div>
        </div>
    </div>
</div>

<form method="get" action="<?= base_url("{$prefix}/products") ?>" class="se-surface p-3 mb-4">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" name="q" value="<?= esc($q) ?>" placeholder="<?= se_t('Cari produk atau slug...', 'Search product or slug...') ?>">
        </div>
        <div class="col-md-2">
            <select name="category_id" class="form-select form-select-sm">
                <option value="0"><?= se_t('Semua Kategori', 'All Categories') ?></option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (int) $categoryId === (int) $c['id'] ? 'selected' : '' ?>>
                        <?= esc(se_localized($c, 'name')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value=""><?= se_t('Semua Status', 'All Availability') ?></option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>><?= se_t('Tersedia', 'Available') ?></option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>><?= se_t('Tidak Tersedia', 'Unavailable') ?></option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="stock" class="form-select form-select-sm">
                <option value=""><?= se_t('Semua Stok', 'All Stock') ?></option>
                <option value="low" <?= $stockState === 'low' ? 'selected' : '' ?>><?= se_t('Stok Menipis (1–5)', 'Low Stock (1–5)') ?></option>
                <option value="out" <?= $stockState === 'out' ? 'selected' : '' ?>><?= se_t('Stok Habis (0)', 'Out of Stock (0)') ?></option>
                <option value="in" <?= $stockState === 'in' ? 'selected' : '' ?>><?= se_t('Stok Aman (>5)', 'Healthy (>5)') ?></option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-se btn-sm flex-fill"><?= se_t('Saring', 'Filter') ?></button>
            <a href="<?= base_url("{$prefix}/products") ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </div>
</form>

<div class="se-surface p-3 p-md-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th><?= se_t('Produk', 'Product') ?></th>
                    <th><?= se_t('Kategori', 'Category') ?></th>
                    <th><?= se_t('Harga', 'Price') ?></th>
                    <th><?= se_t('Stok', 'Stock') ?></th>
                    <th><?= se_t('Status', 'Availability') ?></th>
                    <th class="text-end"><?= se_t('Aksi', 'Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4"><?= se_t('Tidak ada produk ditemukan.', 'No products found.') ?></td></tr>
                <?php endif; ?>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php if ($p['primary_image']): ?>
                                    <img class="table-thumb" src="<?= base_url('uploads/products/' . esc($p['primary_image'])) ?>" alt="<?= esc($p['name']) ?>">
                                <?php else: ?>
                                    <div class="table-thumb d-flex align-items-center justify-content-center bg-light text-muted small">N/A</div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold small"><?= esc(se_localized($p, 'name')) ?></div>
                                    <div class="text-muted" style="font-size:12px;"><code><?= esc($p['slug']) ?></code> · <?= (int) $p['image_count'] ?> <?= se_t('foto', 'photo(s)') ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="small"><?= esc(se_lang() === 'id' && $p['category_name_id'] ? $p['category_name_id'] : $p['category_name']) ?></td>
                        <td class="fw-semibold small">Rp <?= number_format((float) $p['price'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ((int) $p['stock'] === 0): ?>
                                <span class="badge bg-danger-subtle text-danger"><?= se_t('Habis (0)', 'Out (0)') ?></span>
                            <?php elseif ((int) $p['stock'] <= 5): ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis"><?= se_t('Menipis', 'Low') ?> (<?= (int) $p['stock'] ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success"><?= (int) $p['stock'] ?> unit</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= $p['status'] === 'active' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' ?>">
                                <?= $p['status'] === 'active' ? se_t('Tersedia', 'Available') : se_t('Tidak Tersedia', 'Unavailable') ?>
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="<?= base_url('product/' . esc($p['slug'])) ?>" class="btn btn-outline-secondary btn-sm rounded-3" target="_blank"><?= se_t('Lihat', 'View') ?></a>
                            <a href="<?= base_url("{$prefix}/products/{$p['id']}/edit") ?>" class="btn btn-outline-primary btn-sm rounded-3"><?= se_t('Edit', 'Edit') ?></a>
                            <form method="post" action="<?= base_url("{$prefix}/products/{$p['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3"><?= se_t('Hapus', 'Delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
        <div class="small text-muted"><?= se_t('Menampilkan halaman berpaginasi (8 item/halaman).', 'Paginated view (8 items/page).') ?></div>
        <?= $pager->links('products') ?>
    </div>
</div>
<?= $this->endSection() ?>

