<!DOCTYPE html>
<html lang="<?= se_lang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'ShopEase') ?></title>
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
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg se-navbar sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="<?= base_url('/') ?>">
            <span class="se-brand-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </span>
            <span>ShopEase</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sfNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="sfNav">
            <ul class="navbar-nav me-auto ms-lg-4 gap-1">
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= base_url('/') ?>"><?= se_t('Beranda', 'Home') ?></a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="<?= base_url('catalog') ?>"><?= se_t('Katalog', 'Catalog') ?></a></li>
                <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link fw-semibold" style="color:var(--se-primary);" href="<?= base_url('admin/products') ?>"><?= se_t('Kelola Katalog', 'Manage Catalog') ?></a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" style="color:var(--se-primary);" href="<?= base_url('admin/employees') ?>"><?= se_t('Karyawan', 'Employees') ?></a></li>
                <?php elseif (session()->get('role') === 'operator'): ?>
                    <li class="nav-item"><a class="nav-link fw-semibold" style="color:var(--se-primary);" href="<?= base_url('operator/products') ?>"><?= se_t('Ruang Kerja Operator', 'Operator Workspace') ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" aria-label="Language">
                    <a href="<?= base_url('prefs/lang/id') ?>" class="btn <?= se_lang() === 'id' ? 'btn-se' : 'btn-outline-secondary' ?>">ID</a>
                    <a href="<?= base_url('prefs/lang/en') ?>" class="btn <?= se_lang() === 'en' ? 'btn-se' : 'btn-outline-secondary' ?>">EN</a>
                </div>
                <button type="button" id="themeToggleBtn" class="btn btn-outline-secondary btn-sm rounded-3" title="Tema Terang / Gelap">
                    <?= se_t('Tema', 'Theme') ?>
                </button>
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url('profile') ?>" class="btn btn-light border btn-sm rounded-3 fw-semibold">
                        <?= esc(session()->get('name')) ?> (<?= esc(session()->get('role')) ?>)
                    </a>
                    <form method="post" action="<?= base_url('logout') ?>" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3"><?= se_t('Keluar', 'Sign Out') ?></button>
                    </form>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-outline-secondary btn-sm rounded-3"><?= se_t('Masuk', 'Sign In') ?></a>
                    <a href="<?= base_url('register') ?>" class="btn btn-se btn-sm px-3"><?= se_t('Daftar', 'Register') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="py-4 py-md-5 flex-grow-1">
    <div class="container">
        <?= $this->include('partials/alerts') ?>
        <?= $this->renderSection('content') ?>
    </div>
</main>

<footer class="bg-dark text-secondary py-4 mt-auto small">
    <div class="container d-flex flex-wrap justify-content-between gap-2">
        <div><strong class="text-white">ShopEase</strong> — Tugas Minggu 12 PBKK: CRUD Kategori, Produk, Galeri Foto &amp; Karyawan (CodeIgniter 4.7.6)</div>
        <div>Farrel Prastita Ramadhan (5025241219) · Teknik Informatika ITS</div>
    </div>
</footer>

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

