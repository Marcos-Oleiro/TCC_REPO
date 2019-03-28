<?php

use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__FILE__) . '/../libs/AuxFunc.php';

// Routes
$app->get('/hello/{name}', function ($request, $response, $args) {
//    return $response->write("Hello " . $args['name']);
   $string = "hello " . $args['name'];
   return $response->withJson($string);
});
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

    // a resposta deve ir para o front-end em formna de json, então eu faço um array e o transformo em json
    $answer = [];
    // conexão do banco
    $db_con = $this->db;
    
    // vetor com as informações que vem via formulário
    $user_data = $this->request->getParsedBody();
        
    // verifique se os campos estão vazios
    if ( !checkEmptyFields ($user_data) ) {

        // verifica se os dados vieram com os campos necessários
        if (testFieldsNames($user_data)){

            if (!validateNickname($user_data['nickname'])){ // se o nickname não passar no teste, vai entrar nesse if
                
                return $response->withStatus(400);
            }
            elseif (!validateEmail($user_data['email'])){ // se o email não passar no teste, vai entrar nesse if

                return $response->withStatus(400);
            }
            elseif(!validatePasswd($user_data['passwd'])){ // se o passwd não passar no teste, vai entrar nesse if
                
                return $response->withStatus(400);
            }
            else{ // se passar em todos os testes, entra aqui
                               
                if ( !checkNewEmail ($user_data['email'], $db_con)){

                    $body = $response->getBody();
                    
                    $body->write("Email já cadastrado, favor escolher outro");
                    
                    return $response->withStatus(400);
                }
                if ( !checkNewNickname ($user_data['nickname'], $db_con)){
                    
                    $body = $response->getBody();

                    $body->write("Nickname já cadastrado, favor escolher outro");
                    
                    return $response->withStatus(400);
                }

                $user_data['passwd'] = dbPass($user_data['passwd']);
                saveNewUser($user_data['nickname'],$user_data['email'],$user_data['passwd'],$db_con);
                return $response->withStatus(200)->write("ok!");  
            }
        }
        else{
            return $response->withStatus(400);
        }
    }
    else{
        return $response->withStatus(400);
    }
});

// método para validar os dados de login do usuário
$app->post('/login', function (Request $request, Response $response, array $args) {

    // a resposta deve ir para o front-end em formna de json, então eu faço um array e o transformo em json
    $answer = [];

    // conexão do banco
    $db_con = $this->db;
    
    // vetor com as informações que vem via formulário
    $user_data = $this->request->getParsedBody();

    // inserir um novo item dentro do array para poder utilizar as mesmas funções que foram usadas no formulário de registro.
    $user_data['nickname'] = 'nickname';  

    // verifique se os campos estão vazios
    if ( !checkEmptyFields($user_data) ) {

        // verifica se os dados vieram com os campos necessários
        if (testFieldsNames($user_data)){
            
            $db_data = checkUser($user_data['email'],dbPass($user_data['passwd']),$db_con);
            
            $token = jwtBuilder($db_data);
            
             if ( is_numeric($db_data)) { // se os dados estiverem corretos
                 $response = $response->withHeader('id',idEncryptor($db_data))
                                     ->withHeader("Access-Control-Expose-Headers","id");
                 
                 //  transformar o objeto token em string para enviar para o cliente
                 $message = array (
                     'token' => (string)$token
                 );
     
                 $str_json = json_encode($message);            
                 // campos verificados, usuário verificado, token gerado
                 // retorna token e http code 200 - OK
                 return  $response->withJson($str_json)->withStatus(200);

             }
             else{ // dados não batendo com os dados do banco de dados
                 $body = $response->getBody();
                 $body->write($db_data);
                 return $response->withStatus(400);
                 
             }
        }
        else{
            // request inválida
            // http code 400 - bad request
            return $response->withStatus(400);
        }
    }
    else{
        // request inválida
        // http code 400 - bad request
        return $response->withStatus(400);
    }   
});

// retorna as informações  necessárias do usuário com a id informada.
$app->get('/home/{id}', function (Request $request, Response $response, array $args ) {

    // pega a id "encriptada" e transformma em ID numérico
    $id = idDecryptor($args['id']);
    
    // MXRlc3Rl

    $tkn_auth = $request->getHeader("HTTP_AUTHORIZATION")[0];
    
    if (validateAuthType($tkn_auth)){

        $str_token = explode(" ", $tkn_auth)[1];

        if ((validateToken($str_token, $id))) {
            
            // conexão do banco
            $db_con = $this->db;

            return $response->withJson(json_encode(getUserData($id, $db_con)));
        } else {
            return $response->withStatus(401);
        }
    }

    return $response->withStatus(401);
    
});

$app->get('/profile/desc/{id}', function (Request $request, Response $response, array $args) {

    $id = idDecryptor($args['id']);
    
    $tkn_auth = $request->getHeader('HTTP_AUTHORIZATION')[0];

    if (validateAuthType($tkn_auth)) {

        $str_token = explode(" ", $tkn_auth)[1];

        if ((validateToken($str_token, $id))) {
            
            // conexão do banco
            $db_con = $this->db;
            
            // buscarno banco a descrição
            return $response->withJson(json_encode(getUserData($id, $db_con)));
            
        } else {
            return $response->withStatus(401);
        }
    }
    
    
    
    
    die();   
});



$app->put('/profile/edit/desc',function(Request $request, Response $response, array $args) {

    // $new_desc = $this->request->getParsedBody()['new_desc'];
    $db_con = $this->db;
    $_SESSION['logged'] = true;

    // if (isLogged()) {
        
        updateDescription($this->request->getParsedBody()['id'],$this->request->getParsedBody()['new_desc'], $db_con);

    // }
    // else{
        
    // }
  
 

    
    return $this->response->write(print_r($this->request->getParsedBody()));
    // return $this->response->write(updateDescription($id_user,$new_desc, $db_con));
});


$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

