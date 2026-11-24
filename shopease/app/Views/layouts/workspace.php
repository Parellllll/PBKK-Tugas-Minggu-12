<!DOCTYPE html>
<html lang="<?= se_lang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Workspace — ShopEase') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/shopease-crud.css') ?>" rel="stylesheet">
    <script>
        (function () {
            const saved = localStorage.getItem('se_theme');
            if (saved === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>
</head>
<body>
<?php $ws = $prefix ?? se_workspace_prefix(); ?>
<div class="ws-shell">
    <aside class="ws-sidebar">
        <a href="<?= base_url('/') ?>" class="d-flex align-items-center gap-2 text-white text-decoration-none mb-4 px-2">
            <span class="se-brand-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </span>
            <div>
                <div class="fw-bold lh-1">ShopEase</div>
                <small class="text-secondary" style="font-size:11px;"><?= strtoupper($ws) ?> WORKSPACE</small>
            </div>
        </a>

        <nav class="ws-nav flex-grow-1">
            <a href="<?= base_url("{$ws}/products") ?>" class="<?= ($activeNav ?? '') === 'products' ? 'active' : '' ?>">
                <span><?= se_t('Inventaris Produk', 'Product Inventory') ?></span>
            </a>
            <a href="<?= base_url("{$ws}/categories") ?>" class="<?= ($activeNav ?? '') === 'categories' ? 'active' : '' ?>">
                <span><?= se_t('Kategori Produk', 'Categories') ?></span>
            </a>
            <?php if (session()->get('role') === 'admin'): ?>
                <a href="<?= base_url('admin/employees') ?>" class="<?= ($activeNav ?? '') === 'employees' ? 'active' : '' ?>">
                    <span><?= se_t('Direktori Karyawan', 'Employees') ?></span>
                </a>
            <?php endif; ?>
            <hr class="border-secondary border-opacity-25 my-2">
            <a href="<?= base_url('catalog') ?>"><span><?= se_t('Lihat Katalog Publik', 'Public Catalog') ?></span></a>
            <a href="<?= base_url('profile') ?>"><span><?= se_t('Profil Akun', 'My Profile') ?></span></a>
        </nav>

        <div class="pt-3 mt-3 border-top border-secondary border-opacity-25">
            <div class="d-flex gap-2 mb-3">
                <a href="<?= base_url('prefs/lang/id') ?>" class="btn btn-sm flex-fill <?= se_lang() === 'id' ? 'btn-se' : 'btn-outline-light' ?>">ID</a>
                <a href="<?= base_url('prefs/lang/en') ?>" class="btn btn-sm flex-fill <?= se_lang() === 'en' ? 'btn-se' : 'btn-outline-light' ?>">EN</a>
                <button type="button" id="themeToggleBtn" class="btn btn-sm btn-outline-light"><?= se_t('Tema', 'Theme') ?></button>
            </div>
            <div class="small text-white fw-semibold px-1"><?= esc(session()->get('name')) ?></div>
            <div class="small text-secondary px-1 mb-2 text-truncate"><?= esc(session()->get('email')) ?></div>
            <form method="post" action="<?= base_url('logout') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-light btn-sm w-100 rounded-3"><?= se_t('Keluar', 'Sign Out') ?></button>
            </form>
        </div>
    </aside>

    <main class="ws-main">
        <?= $this->include('partials/alerts') ?>
        <?= $this->renderSection('content') ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('themeToggleBtn')?.addEventListener('click', function () {
    const html = document.documentElement;
    const isDark = html.getAttribute('data-theme') === 'dark';
    if (isDark) {
        html.removeAttribute('data-theme');
        localStorage.setItem('se_theme', 'light');
    } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('se_theme', 'dark');
    }
});
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>

