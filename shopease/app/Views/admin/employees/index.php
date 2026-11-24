<?= $this->extend('layouts/workspace') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
        <span class="badge bg-primary-subtle text-primary mb-1">Administrator Only · /admin/employees</span>
        <h1 class="h3 fw-bold mb-1"><?= se_t('Direktori Karyawan', 'Employee Directory') ?></h1>
        <p class="text-muted small mb-0"><?= se_t('Cari karyawan berdasarkan nama, kode, atau departemen serta saring berdasarkan status kepegawaian.', 'Search employees by name, code, or department and filter by employment status.') ?></p>
    </div>
    <a href="<?= base_url('admin/employees/create') ?>" class="btn btn-se px-3 py-2">+ <?= se_t('Tambah Karyawan', 'Add Employee') ?></a>
</div>

<form method="get" action="<?= base_url('admin/employees') ?>" class="se-surface p-3 mb-4">
    <div class="row g-2">
        <div class="col-md-5">
            <input type="text" class="form-control form-control-sm" name="q" value="<?= esc($q) ?>" placeholder="<?= se_t('Cari nama, kode karyawan, atau departemen...', 'Search name, code, or department...') ?>">
        </div>
        <div class="col-md-3">
            <select name="department" class="form-select form-select-sm">
                <option value=""><?= se_t('Semua Departemen', 'All Departments') ?></option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?= esc($d) ?>" <?= $department === $d ? 'selected' : '' ?>><?= esc($d) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value=""><?= se_t('Semua Status', 'All Statuses') ?></option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>><?= se_t('Aktif', 'Active') ?></option>
                <option value="on_leave" <?= $status === 'on_leave' ? 'selected' : '' ?>><?= se_t('Cuti', 'On Leave') ?></option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>><?= se_t('Nonaktif', 'Inactive') ?></option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-se btn-sm flex-fill"><?= se_t('Cari', 'Search') ?></button>
            <a href="<?= base_url('admin/employees') ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </div>
</form>

<div class="se-surface p-3 p-md-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th><?= se_t('Kode', 'Code') ?></th>
                    <th><?= se_t('Nama & Email Kantor', 'Name & Work Email') ?></th>
                    <th><?= se_t('Jabatan', 'Position') ?></th>
                    <th><?= se_t('Departemen', 'Department') ?></th>
                    <th><?= se_t('Status', 'Status') ?></th>
                    <th class="text-end"><?= se_t('Aksi', 'Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employees)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4"><?= se_t('Belum ada data karyawan yang sesuai.', 'No matching employees found.') ?></td></tr>
                <?php endif; ?>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><code><?= esc($emp['employee_code']) ?></code></td>
                        <td>
                            <a href="<?= base_url('admin/employees/' . $emp['id']) ?>" class="fw-semibold text-decoration-none"><?= esc($emp['full_name']) ?></a>
                            <div class="text-muted" style="font-size:12px;"><?= esc($emp['work_email']) ?></div>
                        </td>
                        <td class="small"><?= esc($emp['position']) ?></td>
                        <td class="small"><?= esc($emp['department']) ?></td>
                        <td>
                            <?php
                            $badgeClass = match ($emp['status']) {
                                'active'   => 'bg-success-subtle text-success',
                                'on_leave' => 'bg-warning-subtle text-warning-emphasis',
                                default    => 'bg-secondary-subtle text-secondary',
                            };
                            $label = match ($emp['status']) {
                                'active'   => se_t('Aktif', 'Active'),
                                'on_leave' => se_t('Cuti', 'On Leave'),
                                default    => se_t('Nonaktif', 'Inactive'),
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $label ?></span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="<?= base_url('admin/employees/' . $emp['id']) ?>" class="btn btn-outline-secondary btn-sm rounded-3"><?= se_t('Detail', 'View') ?></a>
                            <a href="<?= base_url('admin/employees/' . $emp['id'] . '/edit') ?>" class="btn btn-outline-primary btn-sm rounded-3"><?= se_t('Edit', 'Edit') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

