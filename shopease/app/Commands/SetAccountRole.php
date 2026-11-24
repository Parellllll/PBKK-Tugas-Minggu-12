<?php

namespace App\Commands;

use App\Models\UserModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SetAccountRole extends BaseCommand
{
    protected $group       = 'ShopEase';
    protected $name        = 'accounts:role';
    protected $description = 'Tetapkan peran (admin, operator, atau customer) pada akun yang sudah terdaftar.';
    protected $usage       = 'accounts:role <email> <admin|operator|customer>';

    public function run(array $params): void
    {
        $email = strtolower(trim((string) ($params[0] ?? '')));
        $role  = strtolower(trim((string) ($params[1] ?? '')));

        if ($email === '' || !in_array($role, ['admin', 'operator', 'customer'], true)) {
            CLI::error('Penggunaan: php spark accounts:role <email> <admin|operator|customer>');
            return;
        }

        $model = new UserModel();
        $user  = $model->where('email', $email)->first();
        if (!$user) {
            CLI::error("Akun dengan email '{$email}' tidak ditemukan.");
            return;
        }

        $model->update($user['id'], ['role' => $role]);
        CLI::write("Peran akun {$user['name']} ({$email}) berhasil diubah dari '{$user['role']}' menjadi '{$role}'.", 'green');
    }
}

