<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('user')) {
            session()->setFlashdata('error', 'Please log in to continue.');
            return redirect()->to(base_url('login'));
        }

        if (session('user')['role'] !== 'admin') {
            session()->setFlashdata('error', 'Access denied. Admin area only.');
            return redirect()->to(base_url('items'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing needed after
    }
}