<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\GlobalvarLib;

class AdminMainFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $globalvar = new GlobalvarLib();
        // 세션설정
        $session = session();
        // var_dump($session->get());
        if ($session->get('mem_type') != 'A' || !(in_array($request->getIPAddress(), [$globalvar->getADevIp()]) || $globalvar->getServerHost() == 'test'
        )) {
            return redirect($globalvar->getMain());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
