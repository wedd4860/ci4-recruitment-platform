<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\GlobalvarLib;

class AdminLoginFilter implements FilterInterface{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        $globalvar = new GlobalvarLib();
        // 세션설정
        $session = session();
        if( $session->get('mem_type') == 'A' && (in_array($request->getIPAddress(), [$globalvar->getADevIp()]) || $globalvar->getServerHost() == 'test'
        )){
            return redirect($globalvar->getAdminMain());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}