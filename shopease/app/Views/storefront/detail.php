<?= $this->extend('layouts/storefront') ?>

<?= $this->section('content') ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>"><?= se_t('Beranda', 'Home') ?></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('catalog') ?>"><?= se_t('Katalog', 'Catalog') ?></a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('catalog?category=' . urlencode($product['category_slug'])) ?>"><?= esc(se_lang() === 'id' && $product['category_name_id'] ? $product['category_name_id'] : $product['category_name']) ?></a></li>
        <li class="breadcrumb-item active"><?= esc(se_localized($product, 'name')) ?></li>
    </ol>
</nav>

<div class="se-surface p-4 p-lg-5 mb-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <?php $firstImg = $images[0]['filename'] ?? null; ?>
            <img id="mainProductImg"
                 src="<?= $firstImg ? base_url('uploads/products/' . esc($firstImg)) : '' ?>"
                 alt="<?= esc(se_localized($product, 'name')) ?>"
                 class="w-100 rounded-4 border mb-3"
                 style="aspect-ratio:16/11; object-fit:cover;">

            <?php if (count($images) > 1): ?>
                <div class="row g-2">
                    <?php foreach ($images as $idx => $img): ?>
                        <div class="col-3">
                            <img src="<?= base_url('uploads/products/' . esc($img['filename'])) ?>"
                                 alt="Foto <?= $idx + 1 ?>"
                                 class="gallery-thumb <?= $idx === 0 ? 'is-primary' : '' ?>"
                                 onclick="document.getElementById('mainProductImg').src=this.src; document.querySelectorAll('.gallery-thumb').forEach(el=>el.classList.remove('is-primary')); this.classList.add('is-primary');">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-6 d-flex flex-column">
            <div class="d-flex flex-wrap gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary"><?= esc(se_lang() === 'id' && $product['category_name_id'] ? $product['category_name_id'] : $product['category_name']) ?></span>
                <?php if ((int) $product['stock'] === 0): ?>
                    <span class="badge bg-danger-subtle text-danger"><?= se_t('Stok Habis', 'Out of Stock') ?></span>
                <?php elseif ((int) $product['stock'] <= 5): ?>
                    <span class="badge bg-warning-subtle text-warning-emphasis"><?= se_t('Stok Menipis: ', 'Low Stock: ') . (int) $product['stock'] ?></span>
                <?php else: ?>
                    <span class="badge bg-success-subtle text-success"><?= se_t('Tersedia: ', 'In Stock: ') . (int) $product['stock'] . ' unit' ?></span>
                <?php endif; ?>
                <span class="badge bg-secondary-subtle text-secondary"><?= count($images) ?> <?= se_t('Foto Galeri', 'Gallery Photo(s)') ?></span>
            </div>

            <h1 class="h3 fw-bold mb-2"><?= esc(se_localized($product, 'name')) ?></h1>
            <?php if (!empty($product['name_id']) && $product['name_id'] !== $product['name']): ?>
                <div class="text-muted small mb-3"><?= esc(se_lang() === 'id' ? $product['name'] : $product['name_id']) ?></div>
            <?php endif; ?>

            <div class="display-6 fw-bold mb-3" style="color:var(--se-primary);">
                Rp <?= number_format((float) $product['price'], 0, ',', '.') ?>
            </div>

            <p class="text-muted mb-4"><?= nl2br(esc($product['description'] ?? '')) ?></p>

            <div class="mt-auto d-flex flex-wrap gap-2">
                <a href="<?= base_url('catalog') ?>" class="btn btn-outline-secondary rounded-3">&larr; <?= se_t('Kembali ke Katalog', 'Back to Catalog') ?></a>
                <?php if (in_array(session()->get('role'), ['admin', 'operator'], true)): ?>
                    <a href="<?= base_url(se_workspace_prefix() . '/products/' . $product['id'] . '/edit') ?>" class="btn btn-se">
                        <?= se_t('Edit Produk & Galeri', 'Edit Product & Gallery') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($related)): ?>
    <h2 class="h5 fw-bold mb-3"><?= se_t('Produk Terkait', 'Related Products') ?></h2>
    <div class="row g-3">
        <?php foreach ($related as $r): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="se-surface h-100 overflow-hidden d-flex flex-column">
                    <img class="product-thumb" src="<?= $r['primary_image'] ? base_url('uploads/products/' . esc($r['primary_image'])) : '' ?>" alt="<?= esc(se_localized($r, 'name')) ?>">
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <div class="fw-bold small mb-1"><?= esc(se_localized($r, 'name')) ?></div>
                        <div class="fw-bold small mb-2" style="color:var(--se-primary);">Rp <?= number_format((float) $r['price'], 0, ',', '.') ?></div>
                        <a href="<?= base_url('product/' . esc($r['slug'])) ?>" class="small fw-semibold text-decoration-none mt-auto"><?= se_t('Lihat →', 'View →') ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>

