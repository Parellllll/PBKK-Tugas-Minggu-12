<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login(): string
    {
        return view('auth/login', [
            'title' => se_t('Masuk — ShopEase', 'Sign In — ShopEase'),
        ]);
    }

    public function authenticate()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email     = strtolower(trim((string) $this->request->getPost('email')));
        $password  = (string) $this->request->getPost('password');
        $throttle  = service('throttler');
        $key       = 't12_login_' . md5($this->request->getIPAddress() . '|' . $email);

        if ($throttle->check($key, 5, 60) === false) {
            return redirect()->back()->withInput()->with('error', se_t('Terlalu banyak percobaan masuk. Coba lagi 1 menit lagi.', 'Too many login attempts. Please try again in 1 minute.'));
        }

        $userModel = new UserModel();
        $user      = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password']) || (int) $user['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', se_t('Email atau kata sandi tidak sesuai.', 'Invalid email or password.'));
        }

        session()->regenerate(true);
        session()->set([
            'logged_in' => true,
            'user_id'   => (int) $user['id'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'role'      => $user['role'],
        ]);

        $dest = match ($user['role']) {
            'admin'    => '/admin/products',
            'operator' => '/operator/products',
            default    => '/',
        };

        return redirect()->to($dest)->with('success', se_t('Selamat datang, ', 'Welcome back, ') . $user['name'] . '!');
    }

    public function register(): string
    {
        return view('auth/register', [
            'title' => se_t('Daftar Akun — ShopEase', 'Create Account — ShopEase'),
        ]);
    }

    public function store()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->insert([
            'name'      => trim((string) $this->request->getPost('name')),
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'phone'     => trim((string) $this->request->getPost('phone')) ?: null,
            'password'  => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'      => 'customer',
            'is_active' => 1,
        ]);

        return redirect()->to('/login')->with('success', se_t('Pendaftaran berhasil! Silakan masuk dengan akun Anda.', 'Registration successful! Please sign in.'));
    }

    public function logout()
    {
        session()->remove(['logged_in', 'user_id', 'name', 'email', 'role', 'intended_url']);
        session()->regenerate(true);

        return redirect()->to('/login')->with('success', se_t('Anda telah keluar dari ShopEase.', 'You have signed out safely.'));
    }

    public function profile(): string
    {
        $user = (new UserModel())->find((int) session()->get('user_id'));

        return view('storefront/profile', [
            'title' => se_t('Profil Akun — ShopEase', 'Account Profile — ShopEase'),
            'user'  => $user,
        ]);
    }

    public function updateProfile()
    {
        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]',
            'phone'   => 'permit_empty|max_length[30]',
            'address' => 'permit_empty|max_length[500]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        (new UserModel())->update((int) session()->get('user_id'), [
            'name'    => $name,
            'phone'   => trim((string) $this->request->getPost('phone')) ?: null,
            'address' => trim((string) $this->request->getPost('address')) ?: null,
        ]);
        session()->set('name', $name);

        return redirect()->to('/profile')->with('success', se_t('Informasi kontak berhasil diperbarui.', 'Contact details updated successfully.'));
    }
}

