<?= $this->extend('layouts/storefront') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="se-surface p-4 p-md-5">
            <span class="badge bg-primary-subtle text-primary mb-2">Role: <?= esc(strtoupper($user['role'])) ?></span>
            <h1 class="h4 fw-bold mb-3"><?= se_t('Informasi Kontak Akun', 'Account Contact Information') ?></h1>
            <form method="post" action="<?= base_url('profile/update') ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" class="form-control bg-light" value="<?= esc($user['email']) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold"><?= se_t('Nama Lengkap', 'Full Name') ?></label>
                    <input type="text" class="form-control" name="name" value="<?= esc(old('name', $user['name'])) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold"><?= se_t('Telepon', 'Phone') ?></label>
                    <input type="text" class="form-control" name="phone" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-semibold"><?= se_t('Alamat', 'Address') ?></label>
                    <textarea class="form-control" name="address" rows="3"><?= esc(old('address', $user['address'] ?? '')) ?></textarea>
                </div>
                <button type="submit" class="btn btn-se px-4"><?= se_t('Simpan Perubahan', 'Save Changes') ?></button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

