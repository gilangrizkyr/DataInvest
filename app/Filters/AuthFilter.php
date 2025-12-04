<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if ($arguments && in_array('guest', $arguments)) {
            if ($session->has('user_id')) {
                return redirect()->to('/');
            }
        } elseif ($arguments && in_array('auth', $arguments)) {
            if (!$session->has('user_id')) {
                return redirect()->to('/auth/login');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}