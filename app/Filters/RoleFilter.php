<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Jika user belum login, redirect ke login
        if (!$session->has('user_id')) {
            // Hanya redirect jika route bukan guest
            return redirect()->to('/auth/login');
        }

        // Jika ada role check
        if ($arguments && $session->has('role')) {
            $userRole = $session->get('role');
            $allowedRoles = is_array($arguments) ? $arguments : [$arguments];

            if (!in_array($userRole, $allowedRoles)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException();
            }
        }

        return null;
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
