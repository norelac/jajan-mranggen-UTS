<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengguna - Admin',
            'users' => $this->userModel->withDeleted()->findAll(),
        ];
        return view('admin/users/index', $data);
    }

    public function create()
    {
        return view('admin/users/create', ['title' => 'Tambah Pengguna']);
    }

    public function store()
    {
        $rules = [
            'full_name' => 'required|min_length[3]',
            'username'  => 'required|min_length[3]|is_unique[users.username]|alpha_numeric',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[6]',
            'role'      => 'required|in_list[admin,penjual,pembeli]',
        ];

        if (! $this->validate($rules)) {
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
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ]);

        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('admin/users/edit', ['title' => 'Edit Pengguna', 'user' => $user]);
    }

    public function update(int $id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/admin/users')->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'full_name' => 'required|min_length[3]',
            'username'  => "required|min_length[3]|is_unique[users.username,id,{$id}]|alpha_numeric",
            'email'     => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'      => 'required|in_list[admin,penjual,pembeli]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'username'  => $this->request->getPost('username'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
            'address'   => $this->request->getPost('address'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ?? 1,
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/users')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        if ($id === session()->get('userId')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
