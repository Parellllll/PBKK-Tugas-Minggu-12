<?= $this->extend('layouts/storefront') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="se-surface p-4 p-md-5">
            <h1 class="h4 fw-bold mb-1"><?= se_t('Masuk ke ShopEase', 'Sign In to ShopEase') ?></h1>
            <p class="text-muted small mb-4"><?= se_t('Pelanggan, operator, dan administrator menggunakan halaman masuk yang sama.', 'Customers, operators, and administrators share the same sign-in page.') ?></p>

            <form method="post" action="<?= base_url('login') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" required placeholder="admin@shopease.local">
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-semibold" for="password"><?= se_t('Kata Sandi', 'Password') ?></label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-se w-100 py-2"><?= se_t('Masuk', 'Sign In') ?></button>
            </form>

            <div class="mt-4 p-3 rounded-3 bg-light border small">
                <div class="fw-bold mb-1"><?= se_t('Akun Demo (Seeder):', 'Demo Accounts:') ?></div>
                <div>Admin: <code>admin@shopease.local</code> / <code>Admin123!</code></div>
                <div>Operator: <code>operator@shopease.local</code> / <code>Operator123!</code></div>
                <div>Customer: <code>customer@shopease.local</code> / <code>Customer123!</code></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

