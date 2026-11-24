<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            session()->set('intended_url', current_url());
            return redirect()->to('/login')
                ->with('warning', 'Silakan masuk terlebih dahulu untuk mengakses ruang kerja tersebut.');
        }

        if (!empty($arguments) && !in_array(session()->get('role'), $arguments, true)) {
            $role = (string) session()->get('role');
            $dest = match ($role) {
                'admin'    => '/admin/products',
                'operator' => '/operator/products',
                default    => '/',
            };

            return redirect()->to($dest)
                ->with('error', 'Akses ditolak! Peran "' . $role . '" tidak memiliki otorisasi untuk halaman tersebut.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

