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

// método para adicionar usuários ao banco de dados  (Registro de novos usuários)
$app->post('/users', function (Request $request, Response $response, array $args) { 

    return $response->withJson($this->request->getParsedBody());

    // // vetor com as informações que vem via formulário
    // $user_data = $this->request->getParsedBody();

    // // string para camulflar a string no banco de dados
    // $stringPass = "nirvana"; 
    // // senha gerada (concatenada com uma string e gerada a hash)
    // $user_data['passwd'] = hash('sha256',$user_data['passwd'] . $stringPass );

    // // verificar se o e-mail já está cadastrado

    // $aux = "example@domain.com";
    // $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
    // // $stmt->bindParam(':email',$user_data['email']);
    // $stmt->bindParam(':email',$aux);
    // $stmt->execute();
    // $row = $stmt->fetch(); // se não for encontrado resultado, o fetch retorna false

    // if ( $row != false ) {
    //     return $this->response->write("E-mail já cadastrado.");
    // }

    // $stmt = null;
    // $row = null;


    // // verificar se o nickname já está cadastrado
    // $stmt = $this->db->prepare("SELECT * FROM users WHERE nickname = :nickname");
    // $stmt->bindParam(':nickname',$user_data['nickname']);
    // $stmt->execute();
    // $row = $stmt->fetch(); // se não for encontrado resultado, o fetch retorna false
    
    // if ( $row != false ) {
    //     return $this->response->write("Nome de usuário já cadastrado.");
    // }

    // // salva no banco
    // $stmt = $this->db->prepare ("INSERT INTO users (nickname, email,passwd) VALUES(:nickname,:email,:passwd)");
    // $stmt->bindParam(':nickname', $user_data['nickname']);
    // $stmt->bindParam(':email', $user_data['email']);
    // $stmt->bindParam(':passwd', $user_data['passwd']);
    // $stmt->execute();
    
    // return $this->response->write("Usuário cadastrado!.");
    // return $this->response->write("Funcionou!");
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
