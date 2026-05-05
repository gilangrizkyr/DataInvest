<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\UnaSSO;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    // =========================
    // LOGIN PAGE
    // =========================
    public function login()
    {
        if ($this->session->has('user_id')) {
            return redirect()->to('/');
        }

        $ssoUrl = null;
        $error = null;

        try {
            $sso = new UnaSSO();
            $ssoUrl = $sso->builtRealm();
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return view('auth/login_modern', [
            'ssoUrl' => $ssoUrl,
            'error'  => $error
        ]);
    }

    // =========================
    // CALLBACK SSO (POPUP MODE)
    // =========================
    public function callback()
    {
        $userEncoded = $this->request->getGet('user');
        $signature   = $this->request->getGet('signature');

        if (!$userEncoded || !$signature) {
            return $this->popupResponse(false, 'Data tidak lengkap');
        }

        $payload = json_decode(base64_decode($userEncoded), true);

        if (!$payload) {
            return $this->popupResponse(false, 'Payload tidak valid');
        }

        // VALIDASI SIGNATURE
        $expectedSignature = hash_hmac(
            'sha256',
            json_encode($payload),
            getenv('SSO_SECRET')
        );

        if (!hash_equals($expectedSignature, $signature)) {
            return $this->popupResponse(false, 'Signature tidak valid');
        }

        // =========================
        // CEK / AUTO REGISTER USER
        // =========================
        $user = $this->userModel->getUserByUsernameOrEmail($payload['email']);

        if (!$user) {
            $userId = $this->userModel->insert([
                'username' => $payload['username'],
                'email'    => $payload['email'],
                'name'     => $payload['nama'],
                'password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'status'   => 'active',
            ]);

            $user = $this->userModel->find($userId);
        }

        // =========================
        // SET SESSION
        // =========================
        $this->session->set([
            'user_id' => $user['id'],
            'user' => [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'name'     => $user['name'],
                'role'     => $payload['role_name'] ?? 'user',
                'photo'    => $payload['photo'] ?? null,
            ],
            'isLoggedIn' => true,
        ]);

        return $this->popupResponse(true);
    }

    // =========================
    // RESPONSE UNTUK POPUP
    // =========================
    private function popupResponse($success = true, $message = '')
    {
        return response()->setBody("
            <script>
                window.opener.postMessage({
                    success: " . ($success ? 'true' : 'false') . ",
                    message: '" . addslashes($message) . "'
                }, '*');
                window.close();
            </script>
        ");
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/auth/login')->with('success', 'Logout berhasil');
    }
}