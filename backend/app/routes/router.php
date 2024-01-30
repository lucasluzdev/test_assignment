<?php

function loadEndpoint(string $controller, string $action)
{
    $controllerNamespace = "app\\controllers\\{$controller}";
    if(!class_exists($controllerNamespace)) {
        throw new Exception("Controller {$controllerNamespace} doesn't exists.");
    }

    $controllerInstance = new $controllerNamespace();

    if (!method_exists($controllerInstance, $action)) {
        throw new Exception("Method {$action} doesn't exists on controller {$controller}");
    }

    $controllerInstance->$action();
}

$router = [
    'GET' => [
        '/' => fn () => loadEndpoint('SubscriptionController', 'readMany'),
        '/readMany' => fn () => loadEndpoint('SubscriptionController', 'readMany'),
        '/readOne' => fn () => loadEndpoint('SubscriptionController', 'readOne'),
        '/readOneByID' => fn () => loadEndpoint('SubscriptionController', 'readOneByID')
    ],
    'POST' => [
        '/create' => fn () => loadEndpoint('SubscriptionController', 'create')
    ],
    'PUT' => [
        '/update' => fn () => loadEndpoint('SubscriptionController', 'update')
    ],
    'DELETE' => [
        '/delete' => fn () => loadEndpoint('SubscriptionController', 'delete')
    ]
];
