<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class LoginFilter implements FilterInterface{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        // 세션설정
        $session = session();
        // var_dump($session->get());
        if( !( $session->has('is_member') && $session->is_member === true ) ){
            return redirect('\App\Controllers\Auth\AuthController::index');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}