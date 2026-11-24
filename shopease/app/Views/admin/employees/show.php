<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="<?= base_url('admin/employees') ?>" class="small fw-semibold text-decoration-none">&larr; <?= se_t('Kembali ke Direktori Karyawan', 'Back to Employee Directory') ?></a>
</div>

<div class="se-surface p-4 p-md-5" style="max-width:740px;">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <span class="badge bg-primary-subtle text-primary mb-2"><?= esc($employee['employee_code']) ?></span>
            <h1 class="h3 fw-bold mb-1"><?= esc($employee['full_name']) ?></h1>
            <div class="text-muted"><?= esc($employee['position']) ?> · <?= esc($employee['department']) ?></div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/employees/' . $employee['id'] . '/edit') ?>" class="btn btn-se btn-sm px-3"><?= se_t('Edit Karyawan', 'Edit Employee') ?></a>
            <form method="post" action="<?= base_url('admin/employees/' . $employee['id'] . '/delete') ?>" onsubmit="return confirm('Konfirmasi hapus data karyawan ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3"><?= se_t('Hapus Data', 'Delete') ?></button>
            </form>
        </div>
    </div>

    <table class="table mb-0">
        <tr><th width="34%"><?= se_t('Email Kantor', 'Work Email') ?></th><td><?= esc($employee['work_email']) ?></td></tr>
        <tr><th><?= se_t('Nomor Telepon', 'Phone') ?></th><td><?= esc($employee['phone'] ?: '-') ?></td></tr>
        <tr><th><?= se_t('Jabatan', 'Position') ?></th><td><?= esc($employee['position']) ?></td></tr>
        <tr><th><?= se_t('Departemen', 'Department') ?></th><td><?= esc($employee['department']) ?></td></tr>
        <tr><th><?= se_t('Tanggal Bergabung', 'Join Date') ?></th><td><?= esc($employee['joined_at']) ?></td></tr>
        <tr><th><?= se_t('Status Kepegawaian', 'Status') ?></th><td><span class="badge bg-success-subtle text-success"><?= esc($employee['status']) ?></span></td></tr>
    </table>
</div>
<?= $this->endSection() ?>

