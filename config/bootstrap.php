<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use lithium\core\ErrorHandler;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;


ErrorHandler::apply('lithium\action\Dispatcher::run', array(), function($info, $params) {

    $run     = new Run;
    $handler = new PrettyPageHandler;
    $jsonHandler = new \Whoops\Handler\JsonResponseHandler();
    $jsonHandler->onlyForAjaxRequests(true);
    $run->pushHandler($handler);
    $run->pushHandler($jsonHandler);

    $request = $params['request'];
    $exception = $info['exception'];

    $handler->addDataTable('Request', array(
        'URL'         => $request->url,
        'Query String'=> isset($request->params['query']) ? $request->params['query'] : '<none>',
        'HTTP Host'   => $request->get('http:host'),
        'HTTP Method' => $request->get('http:method'),
        'Base Path'   => $request->path,
        'Scheme'      => $request->scheme,
        'Port'        => $request->port,
        'Host'        => $request->host,
        'Auth'        => $request->auth,
        'Username'    => $request->username,
        'Password'    => $request->password,
        'Protocol'    => $request->protocol,
        'Version'     => $request->version,
    ));
    $handler->addDataTable('Params', $request->params);
    $handler->addDataTable('Data', $request->data);
    $handler->addDataTable('Headers', $request->headers);
    $handler->addDataTable('Cookies', $request->cookies);
    $handler->addDataTable('Body', $request->body);

    $run->writeToOutput(false);

    return $run->handleException($exception);
});