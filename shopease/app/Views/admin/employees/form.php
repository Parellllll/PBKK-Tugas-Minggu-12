<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<?php $isEdit = !empty($employee); ?>
<div class="mb-3">
    <a href="<?= base_url('admin/employees') ?>" class="small fw-semibold text-decoration-none">&larr; <?= se_t('Kembali ke Direktori Karyawan', 'Back to Employee Directory') ?></a>
</div>

<div class="se-surface p-4 p-md-5" style="max-width:780px;">
    <h1 class="h4 fw-bold mb-1"><?= $isEdit ? se_t('Edit Data Karyawan', 'Edit Employee') : se_t('Tambah Karyawan Baru', 'Add New Employee') ?></h1>
    <p class="text-muted small mb-4"><?= se_t('Kode karyawan dan email kantor harus unik. Nomor telepon bersifat opsional.', 'Employee code and work email must be unique. Phone number is optional.') ?></p>

    <form method="post" action="<?= $isEdit ? base_url("admin/employees/{$employee['id']}/update") : base_url('admin/employees/store') ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold" for="employee_code"><?= se_t('Kode Karyawan', 'Employee Code') ?> *</label>
                <input type="text" class="form-control" id="employee_code" name="employee_code" value="<?= esc(old('employee_code', $employee['employee_code'] ?? '')) ?>" required placeholder="EMP-007">
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold" for="full_name"><?= se_t('Nama Lengkap', 'Full Name') ?> *</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?= esc(old('full_name', $employee['full_name'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" for="work_email"><?= se_t('Email Kantor', 'Work Email') ?> *</label>
                <input type="email" class="form-control" id="work_email" name="work_email" value="<?= esc(old('work_email', $employee['work_email'] ?? '')) ?>" required placeholder="nama@shopease.id">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" for="phone"><?= se_t('Nomor Telepon (Opsional)', 'Phone Number (Optional)') ?></label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= esc(old('phone', $employee['phone'] ?? '')) ?>" placeholder="0812-xxxx-xxxx">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" for="position"><?= se_t('Jabatan', 'Position') ?> *</label>
                <input type="text" class="form-control" id="position" name="position" value="<?= esc(old('position', $employee['position'] ?? '')) ?>" required placeholder="Catalog Specialist">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" for="department"><?= se_t('Departemen', 'Department') ?> *</label>
                <input type="text" class="form-control" id="department" name="department" value="<?= esc(old('department', $employee['department'] ?? '')) ?>" required placeholder="Operations / Warehouse / Merchandising">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" for="joined_at"><?= se_t('Tanggal Bergabung', 'Join Date') ?> *</label>
                <input type="date" class="form-control" id="joined_at" name="joined_at" value="<?= esc(old('joined_at', $employee['joined_at'] ?? date('Y-m-d'))) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold" for="status"><?= se_t('Status Kepegawaian', 'Employment Status') ?> *</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" <?= old('status', $employee['status'] ?? 'active') === 'active' ? 'selected' : '' ?>><?= se_t('Aktif', 'Active') ?></option>
                    <option value="on_leave" <?= old('status', $employee['status'] ?? '') === 'on_leave' ? 'selected' : '' ?>><?= se_t('Cuti', 'On Leave') ?></option>
                    <option value="inactive" <?= old('status', $employee['status'] ?? '') === 'inactive' ? 'selected' : '' ?>><?= se_t('Nonaktif', 'Inactive') ?></option>
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-se px-4"><?= $isEdit ? se_t('Simpan Perubahan', 'Save Changes') : se_t('Simpan Karyawan', 'Create Employee') ?></button>
            <a href="<?= base_url('admin/employees') ?>" class="btn btn-outline-secondary rounded-3"><?= se_t('Batal', 'Cancel') ?></a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

