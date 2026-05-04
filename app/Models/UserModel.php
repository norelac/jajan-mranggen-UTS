<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'username', 'email', 'password', 'full_name',
        'phone', 'address', 'role', 'avatar', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email'     => 'required|valid_email|is_unique[users.email,id,{id}]',
        'full_name' => 'required|min_length[3]|max_length[100]',
        'role'      => 'required|in_list[admin,penjual,pembeli]',
    ];

    protected $validationMessages = [
        'username' => [
            'required'  => 'Username wajib diisi',
            'min_length' => 'Username minimal 3 karakter',
            'is_unique'  => 'Username sudah digunakan',
        ],
        'email' => [
            'required'    => 'Email wajib diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique'   => 'Email sudah terdaftar',
        ],
        'full_name' => [
            'required'  => 'Nama lengkap wajib diisi',
            'min_length' => 'Nama minimal 3 karakter',
        ],
    ];

    protected $skipValidation = false;

    // ─── Custom Methods ──────────────────────────────────────

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    public function getByRole(string $role)
    {
        return $this->where('role', $role)->findAll();
    }

    public function countByRole(string $role): int
    {
        return $this->where('role', $role)->countAllResults();
    }
}
