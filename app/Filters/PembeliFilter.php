<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PembeliFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $role = session()->get('role');
        if ($role !== 'pembeli') {
            return redirect()->to('/')->with('error', 'Akses ditolak. Halaman ini hanya untuk Pembeli.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
