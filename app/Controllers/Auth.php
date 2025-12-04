<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    // LOGIN PAGE
    public function login()
    {
        if ($this->session->has('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    // PROCESS LOGIN
    public function processLogin()
    {
        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user
        $user = $this->userModel->getUserByUsernameOrEmail($username);

        if (!$user) {
            return redirect()->back()->with('error', 'Username atau email tidak ditemukan');
        }

        // Cek status user
        if ($user['status'] === 'inactive') {
            return redirect()->back()->with('error', 'Akun Anda tidak aktif. Hubungi administrator');
        }

        // Verifikasi password
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        // Set session
        $this->session->set([
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/');
    }

    // LOGOUT
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda berhasil logout');
    }

    // FORGOT PASSWORD PAGE
    public function forgotPassword()
    {
        if ($this->session->has('user_id')) {
            return redirect()->to('/');
        }

        return view('auth/forgot_password');
    }

    // PROCESS FORGOT PASSWORD
    public function processForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            // Jangan beri tahu jika email tidak terdaftar (keamanan)
            return redirect()->back()->with('success', 'Jika email terdaftar, link reset akan dikirim');
        }

        // Generate token reset
        $resetToken = bin2hex(random_bytes(32));
        $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Simpan token ke session atau database (opsional: buat tabel password_resets)
        // Untuk sekarang, kita gunakan session
        session()->set("reset_token_{$user['id']}", [
            'token' => $resetToken,
            'expiry' => $resetExpiry,
        ]);

        // Kirim email
        $this->sendResetPasswordEmail($user['email'], $resetToken, $user['id']);

        return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda');
    }

    // RESET PASSWORD PAGE
    public function resetPassword($token = null)
    {
        if ($this->session->has('user_id')) {
            return redirect()->to('/');
        }

        // Validasi token
        if (!$token) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid');
        }

        $data = [
            'token' => $token,
        ];

        return view('auth/reset_password', $data);
    }

    // PROCESS RESET PASSWORD
    public function processResetPassword()
    {
        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        // Validasi token dari session
        $resetTokenData = $this->validateResetToken($token);

        if (!$resetTokenData) {
            return redirect()->to('/auth/login')->with('error', 'Token tidak valid atau sudah kadaluarsa');
        }

        // Update password
        $userId = $resetTokenData['user_id'];
        $hashedPassword = $this->userModel->hashPassword($password);

        $this->userModel->update($userId, [
            'password' => $hashedPassword,
        ]);

        // Hapus token dari session
        session()->remove("reset_token_{$userId}");

        return redirect()->to('/auth/login')->with('success', 'Password berhasil diubah. Silakan login dengan password baru');
    }

    // Helper: Send Reset Password Email
    private function sendResetPasswordEmail($email, $token, $userId)
    {
        $email_service = \Config\Services::email();
        $email_service->setFrom('your_gmail@gmail.com', 'SST Application');
        $email_service->setTo($email);
        $email_service->setSubject('Reset Password - SST Application');

        $resetLink = base_url("auth/reset-password/{$token}");
        $body = view('auth/email_reset_password', [
            'resetLink' => $resetLink,
            'expiryTime' => '1 jam',
        ]);

        $email_service->setMessage($body);

        if (!$email_service->send()) {
            log_message('error', 'Email gagal dikirim: ' . $email_service->printDebugger());
            return false;
        }

        return true;
    }

    // Helper: Validate Reset Token
    private function validateResetToken($token)
    {
        // Cari token di semua session reset
        $sessionData = session()->getSessionData();

        foreach ($sessionData as $key => $value) {
            if (strpos($key, 'reset_token_') === 0 && is_array($value)) {
                if ($value['token'] === $token && strtotime($value['expiry']) > time()) {
                    $userId = str_replace('reset_token_', '', $key);
                    return [
                        'user_id' => $userId,
                        'valid' => true,
                    ];
                }
            }
        }

        return false;
    }
}
