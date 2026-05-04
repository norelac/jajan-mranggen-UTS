<?php

namespace App\Controllers\Pembeli;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user = $this->userModel->find(session()->get('userId'));
        return view('pembeli/profile', ['title' => 'Profil Saya', 'user' => $user]);
    }

    public function update()
    {
        $userId = session()->get('userId');
        $rules  = [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'phone'     => 'permit_empty|max_length[20]',
            'address'   => 'permit_empty',
            'avatar'    => 'is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png,image/webp]|max_size[avatar,1024]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'phone'     => $this->request->getPost('phone'),
            'address'   => $this->request->getPost('address'),
        ];

        $password = $this->request->getPost('password');
        if ($password) {
            if (strlen($password) < 6) {
                return redirect()->back()->with('error', 'Password minimal 6 karakter.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && ! $avatar->hasMoved()) {
            $user = $this->userModel->find($userId);
            if ($user['avatar'] && file_exists(FCPATH . $user['avatar'])) {
                unlink(FCPATH . $user['avatar']);
            }
            $newName = $avatar->getRandomName();
            $avatar->move(FCPATH . 'uploads/avatars', $newName);
            $data['avatar'] = 'uploads/avatars/' . $newName;
            session()->set('avatar', $data['avatar']);
        }

        $this->userModel->update($userId, $data);
        session()->set('fullName', $data['full_name']);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
