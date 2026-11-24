<?= $this->extend('layouts/storefront') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="se-surface p-4 p-md-5">
            <h1 class="h4 fw-bold mb-1"><?= se_t('Daftar Akun Pelanggan', 'Register Customer Account') ?></h1>
            <p class="text-muted small mb-4"><?= se_t('Sudah memiliki akun?', 'Already have an account?') ?> <a href="<?= base_url('login') ?>"><?= se_t('Masuk', 'Sign In') ?></a></p>

            <form method="post" action="<?= base_url('register') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold"><?= se_t('Nama Lengkap', 'Full Name') ?></label>
                    <input type="text" class="form-control" name="name" value="<?= esc(old('name')) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= esc(old('email')) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold"><?= se_t('Telepon', 'Phone') ?></label>
                    <input type="text" class="form-control" name="phone" value="<?= esc(old('phone')) ?>">
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label small fw-semibold"><?= se_t('Kata Sandi', 'Password') ?></label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold"><?= se_t('Konfirmasi', 'Confirm') ?></label>
                        <input type="password" class="form-control" name="password_confirm" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-se w-100 py-2"><?= se_t('Daftar Sekarang', 'Create Account') ?></button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

