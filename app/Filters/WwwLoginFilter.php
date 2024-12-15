<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\GlobalvarLib;

class WwwLoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $globalvar = new GlobalvarLib();
        // 로그인 체크
        $session = session();
        if (!$session->has('idx') && !in_array($session->get('mem_type'), ['M'])) {
            return redirect()->to($globalvar->getWwwUrl());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
