<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EmployeeController extends BaseController
{
    public function index(): string
    {
        $model = new EmployeeModel();
        $q     = trim((string) $this->request->getGet('q'));
        $dept  = trim((string) $this->request->getGet('department'));
        $stat  = trim((string) $this->request->getGet('status'));

        $departments = $model->distinctDepartments();

        if ($q !== '') {
            $model->groupStart()
                ->like('full_name', $q)
                ->orLike('employee_code', $q)
                ->orLike('department', $q)
                ->orLike('position', $q)
                ->groupEnd();
        }
        if ($dept !== '') {
            $model->where('department', $dept);
        }
        if (in_array($stat, ['active', 'on_leave', 'inactive'], true)) {
            $model->where('status', $stat);
        }

        $employees = $model->orderBy('employee_code', 'ASC')->findAll();

        return view('admin/employees/index', [
            'title'       => se_t('Direktori Karyawan — Admin ShopEase', 'Employee Directory — Admin ShopEase'),
            'activeNav'   => 'employees',
            'prefix'      => 'admin',
            'employees'   => $employees,
            'departments' => $departments,
            'q'           => $q,
            'department'  => $dept,
            'status'      => $stat,
        ]);
    }

    public function create(): string
    {
        return view('admin/employees/form', [
            'title'     => se_t('Tambah Karyawan — ShopEase', 'Add Employee — ShopEase'),
            'activeNav' => 'employees',
            'prefix'    => 'admin',
            'employee'  => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'employee_code' => 'required|min_length[3]|max_length[32]|is_unique[employees.employee_code]',
            'full_name'     => 'required|min_length[3]|max_length[120]',
            'work_email'    => 'required|valid_email|is_unique[employees.work_email]',
            'phone'         => 'permit_empty|max_length[30]',
            'position'      => 'required|min_length[2]|max_length[100]',
            'department'    => 'required|min_length[2]|max_length[80]',
            'joined_at'     => 'required|valid_date[Y-m-d]',
            'status'        => 'required|in_list[active,on_leave,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new EmployeeModel();
        $id    = (int) $model->insert([
            'employee_code' => strtoupper(trim((string) $this->request->getPost('employee_code'))),
            'full_name'     => trim((string) $this->request->getPost('full_name')),
            'work_email'    => strtolower(trim((string) $this->request->getPost('work_email'))),
            'phone'         => trim((string) $this->request->getPost('phone')) ?: null,
            'position'      => trim((string) $this->request->getPost('position')),
            'department'    => trim((string) $this->request->getPost('department')),
            'joined_at'     => (string) $this->request->getPost('joined_at'),
            'status'        => (string) $this->request->getPost('status'),
        ], true);

        return redirect()->to("/admin/employees/{$id}")
            ->with('success', se_t('Data karyawan baru berhasil ditambahkan.', 'Employee added successfully.'));
    }

    public function show(int $id): string
    {
        $employee = (new EmployeeModel())->find($id);
        if (!$employee) {
            throw PageNotFoundException::forPageNotFound("Karyawan #{$id} tidak ditemukan.");
        }

        return view('admin/employees/show', [
            'title'     => $employee['full_name'] . ' — Direktori Karyawan',
            'activeNav' => 'employees',
            'prefix'    => 'admin',
            'employee'  => $employee,
        ]);
    }

    public function edit(int $id): string
    {
        $employee = (new EmployeeModel())->find($id);
        if (!$employee) {
            throw PageNotFoundException::forPageNotFound("Karyawan #{$id} tidak ditemukan.");
        }

        return view('admin/employees/form', [
            'title'     => se_t('Edit Karyawan — ShopEase', 'Edit Employee — ShopEase'),
            'activeNav' => 'employees',
            'prefix'    => 'admin',
            'employee'  => $employee,
        ]);
    }

    public function update(int $id)
    {
        $model    = new EmployeeModel();
        $employee = $model->find($id);
        if (!$employee) {
            throw PageNotFoundException::forPageNotFound("Karyawan #{$id} tidak ditemukan.");
        }

        $rules = [
            'employee_code' => "required|min_length[3]|max_length[32]|is_unique[employees.employee_code,id,{$id}]",
            'full_name'     => 'required|min_length[3]|max_length[120]',
            'work_email'    => "required|valid_email|is_unique[employees.work_email,id,{$id}]",
            'phone'         => 'permit_empty|max_length[30]',
            'position'      => 'required|min_length[2]|max_length[100]',
            'department'    => 'required|min_length[2]|max_length[80]',
            'joined_at'     => 'required|valid_date[Y-m-d]',
            'status'        => 'required|in_list[active,on_leave,inactive]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'employee_code' => strtoupper(trim((string) $this->request->getPost('employee_code'))),
            'full_name'     => trim((string) $this->request->getPost('full_name')),
            'work_email'    => strtolower(trim((string) $this->request->getPost('work_email'))),
            'phone'         => trim((string) $this->request->getPost('phone')) ?: null,
            'position'      => trim((string) $this->request->getPost('position')),
            'department'    => trim((string) $this->request->getPost('department')),
            'joined_at'     => (string) $this->request->getPost('joined_at'),
            'status'        => (string) $this->request->getPost('status'),
        ]);

        return redirect()->to("/admin/employees/{$id}")
            ->with('success', se_t('Data karyawan berhasil diperbarui.', 'Employee updated successfully.'));
    }

    public function delete(int $id)
    {
        $model    = new EmployeeModel();
        $employee = $model->find($id);
        if (!$employee) {
            return redirect()->to('/admin/employees')->with('error', 'Data karyawan tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/admin/employees')
            ->with('success', se_t("Karyawan \"{$employee['full_name']}\" berhasil dihapus.", "Employee \"{$employee['full_name']}\" deleted."));
    }
}

