<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $allowedFields = ['username', 'email', 'password', 'role', 'status', 'last_login'];

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]|alpha_numeric',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[superadmin,admin,user]',
        'status' => 'in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'is_unique' => 'Username sudah terdaftar',
            'alpha_numeric' => 'Username hanya boleh alfanumerik',
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar',
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 8 karakter',
        ],
        'role' => [
            'required' => 'Role harus dipilih',
            'in_list' => 'Role tidak valid',
        ],
    ];

    // Fungsi helper untuk validasi update tanpa password
    public function getValidationRulesUpdate()
    {
        return [
            'username' => 'min_length[3]|max_length[100]|is_unique[users.username,id,{id}]|alpha_numeric',
            'email' => 'valid_email|is_unique[users.email,id,{id}]',
            'role' => 'in_list[superadmin,admin,user]',
            'status' => 'in_list[active,inactive]',
        ];
    }

    // Hash password sebelum insert/update
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    // Verify password
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    // Get user by username atau email
    public function getUserByUsernameOrEmail($identifier)
    {
        return $this->where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    // Update last login
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    // Get all users with pagination
    public function getAllUsers($page = 1, $perPage = 10)
    {
        return $this->paginate($perPage, 'default', $page);
    }

    // Search users
    public function searchUsers($keyword, $page = 1, $perPage = 10)
    {
        return $this->like('username', $keyword)
            ->orLike('email', $keyword)
            ->paginate($perPage, 'default', $page);
    }
}