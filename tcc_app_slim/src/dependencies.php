<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function($c) {
    $db = $c['settings']['db'];
    $host = "tccappdb.c9gqmcom8enw.us-east-2.rds.amazonaws.com";
    $dbname = "tcc_marcos";
    $user = "tcc_marcos";
    $pass = "!Aa456202";
    $pdo = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname,$user, $pass);
    // PDO("pgsql:host=localhost;port=;dbname=bancoteste;user=root;password=12345
    // $pdo = new PDO("pgsql:host=" . $host . ";dbname=" . $dbname . "; user=" . $user . ";password=" . $pass);
    //  $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  return $pdo;
};
// $container['db'] = function($c) {
//     $db = $c['settings']['db'];
//     $host = "localhost";
//     $dbname = "tcc_app";
//     $user = "popoststgres";
//     $pass = "postgres";
//     $pdo = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname,$user, $pass);
//     // PDO("pgsql:host=localhost;port=;dbname=bancoteste;user=root;password=12345
//     // $pdo = new PDO("pgsql:host=" . $host . ";dbname=" . $dbname . "; user=" . $user . ";password=" . $pass);
//     //  $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//   return $pdo;
// };