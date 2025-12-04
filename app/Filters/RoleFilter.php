<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/auth/login');
        }

        if ($arguments) {
            $userRole = session()->get('role');
            $allowedRoles = $arguments;

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