<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\GlobalvarLib;

class WwwNotLoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $globalvar = new GlobalvarLib();
        // 로그인 중이면 페이지 접근 못함
        $session = session();
        if ($session->has('idx')) {
            return redirect()->to($globalvar->getWwwUrl());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
