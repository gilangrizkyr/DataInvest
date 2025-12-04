<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserManagement extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();

        // Hanya superadmin yang bisa akses
        if (session()->get('role') !== 'superadmin') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    // LIST USERS
    public function index()
    {
        $page = $this->request->getVar('page') ?? 1;
        $search = $this->request->getVar('search') ?? '';

        $data = [
            'title' => 'Manajemen User',
            'users' => $search ? $this->userModel->searchUsers($search, $page) : $this->userModel->getAllUsers($page),
            'search' => $search,
            'pager' => $this->userModel->pager,
        ];

        return view('user_management/index', $data);
    }

    // CREATE USER PAGE
    public function create()
    {
        $data = [
            'title' => 'Tambah User Baru',
        ];

        return view('user_management/create', $data);
    }

    // STORE USER
    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]|alpha_numeric',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[admin,user]', // Superadmin tidak bisa dibuat dari sini
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->userModel->hashPassword($this->request->getPost('password')),
            'role' => $this->request->getPost('role'),
            'status' => 'active',
        ];

        if ($this->userModel->insert($data)) {
            $generatedPassword = $this->request->getPost('password');
            return redirect()->to('user-management')->with('success', 
                "User berhasil dibuat!\n\nUsername: {$data['username']}\nPassword: {$generatedPassword}\n\nBagikan informasi ini kepada user");
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat user');
        }
    }

    // EDIT USER PAGE
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
        ];

        return view('user_management/edit', $data);
    }

    // UPDATE USER
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $rules = $this->userModel->getValidationRulesUpdate();
        $rules['id'] = "required|is_in[{$id}]";

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username') ?? $user['username'],
            'email' => $this->request->getPost('email') ?? $user['email'],
            'role' => $this->request->getPost('role') ?? $user['role'],
            'status' => $this->request->getPost('status') ?? $user['status'],
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->userModel->hashPassword($this->request->getPost('password'));
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('user-management')->with('success', 'User berhasil diperbarui');
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui user');
        }
    }

    // DELETE USER
    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to('user-management')->with('success', 'User berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus user');
        }
    }
}