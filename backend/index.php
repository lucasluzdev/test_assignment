<?php

require_once("./vendor/autoload.php");
require_once("./app/routes/router.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json; charset=utf-8');


if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    $_SESSION['id'] = session_id();
}

function countUriItems($uri)
{

    preg_match_all('/\//', $uri, $matches);
    $count = count($matches[0]) + 1;
    return $count;
}

try {
    $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
    $request = $_SERVER["REQUEST_METHOD"];

    if (!isset($router[$request])) {
        throw new Exception("A rota não existe");
    }

    if ($request == "DELETE") {
        $uri = '/' . explode("/", $uri)[1];
    }

    if (!array_key_exists($uri, $router[$request])) {
        throw new Exception("A rota não existe");
    }

    $controller = $router[$request][$uri];
    $controller();
} catch (Exception $e) {
    $e->getMessage();
}
