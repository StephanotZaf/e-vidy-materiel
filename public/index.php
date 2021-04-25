<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

// if (isset($_SERVER['HTTP_ORIGIN'])) {
//     // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
//     // you want to allow, and if so:
//     header('Access-Control-Allow-Headers: Accept,Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
//     header("Access-Control-Allow-Origin: *");
//     // header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//     header("Access-Control-Allow-Methods: GET, POST,PUT, PATCH, HEAD, OPTIONS,DELETE");
//     // header('Access-Control-Allow-Credentials: true');
//     // header('Access-Control-Max-Age: 86400'); // cache for 1 day
// }

// if ( $_SERVER['REQUEST_METHOD'] == 'OPTIONS' ) {
//     header('Access-Control-Allow-Headers: Accept,Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
//     header("Access-Control-Allow-Origin: *");
//     header("Access-Control-Allow-Methods: GET, POST,PUT, PATCH, HEAD, OPTIONS,DELETE");
// }

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
