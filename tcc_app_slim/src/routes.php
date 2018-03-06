<?php

use Slim\Http\Request;
use Slim\Http\Response;


// Routes
//$app->get('/hello/{name}', function ($request, $response, $args) {
////    return $response->write("Hello " . $args['name']);
//    $string = "hello " . $args['name'];
//    return $response->withJson($string);
//});
$app->get('/games', function (Request $request, Response $response, array $args) {

    $stmt = $this->db->query("SELECT * FROM games");
    $games = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $games[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
    return $response->withJson($games);

});
$app->post('/users', function (Request $request, Response $response, array $args) { 
    // $aux = $this->request->getBody();
    $aux = $this->request->getParsedBody();
    $aux2 ;

    return $this->response->write(print_r(array_keys($aux)));

});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
