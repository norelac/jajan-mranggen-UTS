<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }
        return view('auth/login');
    }

    public function loginProcess()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        $messages = [
            'email'    => ['required' => 'Email wajib diisi', 'valid_email' => 'Format email tidak valid'],
            'password' => ['required' => 'Password wajib diisi', 'min_length' => 'Password minimal 6 karakter'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        if (! $user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda tidak aktif. Hubungi admin.');
        }

        // Set session
        session()->set([
            'isLoggedIn' => true,
            'userId'     => $user['id'],
            'username'   => $user['username'],
            'fullName'   => $user['full_name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'avatar'     => $user['avatar'],
        ]);

        session()->setFlashdata('success', 'Selamat datang kembali, ' . $user['full_name'] . '!');

        return $this->redirectByRole();
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }
        return view('auth/register');
    }

    public function registerProcess()
    {
        $rules = [
            'full_name'        => 'required|min_length[3]|max_length[100]',
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]|alpha_numeric',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role'             => 'required|in_list[pembeli,penjual]',
        ];

        $messages = [
            'username'         => ['is_unique' => 'Username sudah digunakan', 'alpha_numeric' => 'Username hanya boleh huruf dan angka'],
            'email'            => ['is_unique' => 'Email sudah terdaftar'],
            'confirm_password' => ['matches' => 'Konfirmasi password tidak cocok'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'full_name' => $this->request->getPost('full_name'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'     => $this->request->getPost('phone'),
            'address'   => $this->request->getPost('address'),
            'role'      => $this->request->getPost('role'),
            'is_active' => 1,
        ]);

        return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah logout.');
    }

    private function redirectByRole()
    {
        return match (session()->get('role')) {
            'admin'   => redirect()->to('/admin/dashboard'),
            'penjual' => redirect()->to('/penjual/dashboard'),
            default   => redirect()->to('/pembeli/dashboard'),
        };
    }
}
