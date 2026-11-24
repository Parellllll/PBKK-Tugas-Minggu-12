<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<?php $isEdit = !empty($product); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <a href="<?= base_url("{$prefix}/products") ?>" class="small fw-semibold text-decoration-none">&larr; <?= se_t('Kembali ke Inventaris Produk', 'Back to Inventory') ?></a>
    <?php if ($isEdit): ?>
        <a href="<?= base_url('product/' . esc($product['slug'])) ?>" class="btn btn-outline-secondary btn-sm rounded-3"><?= se_t('Buka Halaman Detail Katalog ↗', 'View Public Detail Page ↗') ?></a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="<?= $isEdit ? 'col-lg-7' : 'col-lg-8' ?>">
        <div class="se-surface p-4">
            <h1 class="h4 fw-bold mb-1"><?= $isEdit ? se_t('Edit Produk', 'Edit Product') : se_t('Tambah Produk Baru', 'Create New Product') ?></h1>
            <p class="text-muted small mb-4"><?= se_t('Atur nama, kategori, harga dalam Rupiah, stok, serta status ketersediaan produk.', 'Configure product title, category, IDR price, stock count, and catalog availability.') ?></p>

            <form method="post"
                  action="<?= $isEdit ? base_url("{$prefix}/products/{$product['id']}/update") : base_url("{$prefix}/products/store") ?>"
                  enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="name"><?= se_t('Nama Produk Utama', 'Primary Product Name') ?> *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name', $product['name'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="name_id"><?= se_t('Nama Bahasa Indonesia (Opsional)', 'Indonesian Name (Optional)') ?></label>
                        <input type="text" class="form-control" id="name_id" name="name_id" value="<?= esc(old('name_id', $product['name_id'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="category_id"><?= se_t('Kategori', 'Category') ?> *</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- <?= se_t('Pilih Kategori', 'Select Category') ?> --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>" <?= (int) old('category_id', $product['category_id'] ?? 0) === (int) $c['id'] ? 'selected' : '' ?>>
                                    <?= esc(se_localized($c, 'name')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="status"><?= se_t('Ketersediaan', 'Availability') ?> *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active" <?= old('status', $product['status'] ?? 'active') === 'active' ? 'selected' : '' ?>><?= se_t('Tersedia (Tampil di Katalog)', 'Available (Visible)') ?></option>
                            <option value="inactive" <?= old('status', $product['status'] ?? '') === 'inactive' ? 'selected' : '' ?>><?= se_t('Tidak Tersedia (Disembunyikan)', 'Unavailable (Hidden)') ?></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="price"><?= se_t('Harga (Rp)', 'Price (IDR)') ?> *</label>
                        <input type="number" class="form-control" id="price" name="price" min="0" step="500" value="<?= esc(old('price', isset($product['price']) ? (int) $product['price'] : '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold" for="stock"><?= se_t('Jumlah Stok', 'Stock Quantity') ?> *</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?= esc(old('stock', $product['stock'] ?? 10)) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold" for="description"><?= se_t('Deskripsi Produk', 'Product Description') ?></label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= esc(old('description', $product['description'] ?? '')) ?></textarea>
                    </div>

                    <?php if (!$isEdit): ?>
                        <div class="col-12">
                            <label class="form-label small fw-semibold" for="createImages"><?= se_t('Foto Produk Awal (Maks. 6 file JPEG/PNG/WebP, maks 2 MiB/file)', 'Initial Product Photos (Up to 6 JPEG/PNG/WebP, max 2 MiB each)') ?></label>
                            <input type="file" class="form-control" id="createImages" name="images[]" multiple accept="image/jpeg,image/png,image/webp">
                            <div id="createPreview" class="row g-2 mt-2"></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-se px-4"><?= $isEdit ? se_t('Simpan Perubahan', 'Save Changes') : se_t('Simpan & Lanjutkan', 'Create Product') ?></button>
                    <a href="<?= base_url("{$prefix}/products") ?>" class="btn btn-outline-secondary rounded-3"><?= se_t('Batal', 'Cancel') ?></a>
                </div>
            </form>
        </div>
    </div>

    <?php if ($isEdit): ?>
    <div class="col-lg-5">
        <div class="se-surface p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h5 fw-bold mb-0"><?= se_t('Galeri Foto Produk', 'Product Photo Gallery') ?></h2>
                <span class="badge bg-primary-subtle text-primary"><?= count($images) ?> / 6</span>
            </div>
            <p class="text-muted small mb-3"><?= se_t('Gambar utama tampil pada kartu katalog dan urutan pertama galeri detail.', 'The primary image appears on product cards and first in the detail gallery.') ?></p>

            <?php if (count($images) < 6): ?>
            <form method="post" action="<?= base_url("{$prefix}/products/{$product['id']}/images/upload") ?>" enctype="multipart/form-data" class="mb-4 p-3 rounded-3 bg-light border">
                <?= csrf_field() ?>
                <label class="form-label small fw-semibold" for="galleryUpload"><?= se_t('Unggah Foto Baru (JPEG, PNG, WebP · maks 2 MiB)', 'Upload New Photos (JPEG, PNG, WebP · max 2 MiB)') ?></label>
                <input type="file" class="form-control form-control-sm mb-2" id="galleryUpload" name="images[]" multiple accept="image/jpeg,image/png,image/webp" required>
                <div id="galleryPreview" class="row g-2 mb-2"></div>
                <button type="submit" class="btn btn-se btn-sm w-100"><?= se_t('Unggah ke Galeri', 'Upload Photos') ?></button>
            </form>
            <?php endif; ?>

            <div class="row g-3">
                <?php if (empty($images)): ?>
                    <div class="col-12 text-center text-muted small py-3"><?= se_t('Belum ada foto produk.', 'No product photos uploaded yet.') ?></div>
                <?php endif; ?>
                <?php foreach ($images as $img): ?>
                    <div class="col-6">
                        <div class="border rounded-3 p-2 h-100 d-flex flex-column">
                            <img src="<?= base_url('uploads/products/' . esc($img['filename'])) ?>" alt="Foto" class="gallery-thumb <?= (int) $img['is_primary'] === 1 ? 'is-primary' : '' ?> mb-2">
                            <div class="mt-auto d-grid gap-1">
                                <?php if ((int) $img['is_primary'] === 1): ?>
                                    <span class="badge bg-primary w-100 py-1"><?= se_t('★ Gambar Utama', '★ Primary Image') ?></span>
                                <?php else: ?>
                                    <form method="post" action="<?= base_url("{$prefix}/products/{$product['id']}/images/{$img['id']}/primary") ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-primary btn-sm w-100" style="font-size:11px;"><?= se_t('Jadikan Utama', 'Set Primary') ?></button>
                                    </form>
                                <?php endif; ?>
                                <form method="post" action="<?= base_url("{$prefix}/products/{$product['id']}/images/{$img['id']}/delete") ?>" onsubmit="return confirm('Hapus foto ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="font-size:11px;"><?= se_t('Hapus Foto', 'Delete') ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function bindPreview(inputId, containerId) {
    const input = document.getElementById(inputId);
    const box = document.getElementById(containerId);
    if (!input || !box) return;
    input.addEventListener('change', function () {
        box.innerHTML = '';
        Array.from(this.files || []).slice(0, 6).forEach(function (file) {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const col = document.createElement('div');
                col.className = 'col-4';
                col.innerHTML = '<img src="' + e.target.result + '" class="gallery-thumb border" alt="Preview">';
                box.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
}
bindPreview('createImages', 'createPreview');
bindPreview('galleryUpload', 'galleryPreview');
</script>
<?= $this->endSection() ?>

