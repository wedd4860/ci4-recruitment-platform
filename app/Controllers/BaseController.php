<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Libraries\GlobalvarLib;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['alert', 'array', 'form', 'url', 'date', 'ajax', 'str', 'cookie'];

    // globalvar
    // protected $globalvar;
    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        // 공통 변수 추가
        $this->globalvar = new GlobalvarLib();
        $this->viewData = [
            'url' => [
                'www' => $this->globalvar->getWwwUrl(),
                'menu' => $this->globalvar->getMenuUrl(),
                'mediaFull'=>$this->globalvar->getMediaFullUrl(),
                'media'=>$this->globalvar->getMediaUrl(),
            ],
            'config' => $this->globalvar->getConfig()
        ];
        // Preload any models, libraries, etc, here.
        // $this->session = session();
    }
}
