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
    // return $this->response->withJson($this->request->getParsedBody());

    // verifique se os campos estão vazios
    if ( !checkEmptyFields ($user_data) ) {

        // verifica se os dados vieram com os campos necessários
        if (testFieldsNames($user_data)){

            if (!validateNickname($user_data['nickname'])){ // se o nickname não passar no teste, vai entrar nesse if
                // Fazer retorno, informando o erro
                $answer['message'] = "O nome de usuário não é válido";
                $json = json_encode($answer);
                return $this->response->withJson($json);
                // return $this->response->write("O nome de usuário não é válido");
            }
            elseif (!validateEmail($user_data['email'])){ // se o email não passar no teste, vai entrar nesse if
                // Fazer retorno, informando o erro
                $answer['message'] = "O e-mail não é válido";
                $json = json_encode($answer);
                return $this->response->withJson($json);
                // return $this->response->write("O e-mail não é válido");
            }
            elseif(!validatePasswd($user_data['passwd'])){ // se o passwd não passar no teste, vai entrar nesse if
                // Fazer retorno, informando o erro
                $answer['message'] = "O senha não é válida";
                $json = json_encode($answer);
                return $this->response->withJson($json);
                // return $this->response->write("A senha não é válida");
            }
            else{ // se passar em todos os testes, entra aqui
                // Se passar em todos os testes, é necessário  verificar se o email e o nickname já estão cadastados
                $new_user_return = checkNewUser($user_data['email'],$user_data['nickname'],$db_con);
                
                if ( $new_user_return == "OK") { // se o nickname e email já não estiver cadastrado, entra nesse IF. Aqui é feita a inserção no banco de dados.
                    // $stringPass = "nirvana"; 
                    $user_data['passwd'] = dbPass($user_data['passwd']);
                    saveNewUser($user_data['nickname'],$user_data['email'],$user_data['passwd'],$db_con);
                    $answer['message'] = "Salvou";
                    $json = json_encode($answer);
                    logIn(); // "Setando" o valor na session como logado
                    return $this->response->withJson($json);
                    // return $this->response->write("Salvou");
                }
                else{ // email e/ou nickname já cadastrados
                    // retornar a mensagem de erro.
                    $answer['message'] = "Nome de usuário e/ou e-mail já cadastrados";
                    $json = json_encode($answer);
                    return $this->response->withJson($json);
                    // return $this->response->write("Nome de usuário e/ou e-mail já cadastrados");
                }
            }
        }
        else{
            // campos com nomes errados
            // Fazer retorno, informando o erro
            $answer['message'] = "Erro nas informações";
            $json = json_encode($answer);
            return $this->response->withJson($json);
            // return $this->response->write("Erro nas informações");
        }
    }
    else{
        // dados com campos vazios
        // Fazer retorno, informando o erro
        $answer['message'] = "Erro nas informações";
        $json = json_encode($answer);
        return $this->response->withJson($json);
        // return $this->response->write("Erro nas informações");
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
            // return $this->response->write("Campos OK");
            // return $this->response->write(checkUser($user_data['email'],dbPass($user_data['passwd']),$db_con));
            $db_data = checkUser($user_data['email'],dbPass($user_data['passwd']),$db_con);
            // return $this->response->withJson($db_data);
            $answer['message'] = $db_data;
            $json = json_encode($answer);
            logIn(); // "Setando" o valor na session como logado
            return $this->response->withJson($json);
        }
        else{
            // return $this->response->write("Campos Errados");
            $answer['message'] = 'Campos Incorretos';
            $json = json_encode($answer);
            return $this->response->withJson($json);
        }
    }
    else{
        // return $this->response->write("Campos vazios");
        $answer['message'] = 'Informação incorreta';
        $json = json_encode($answer);
        return $this->response->withJson($json);
    }   



    
});


$app->get('/home/{id}', function (Request $request, Response $response, array $args ) {

    $id = intval($args['id']);

    // conexão do banco
    $db_con = $this->db;

    
    return $this->response->withJson(json_encode(getUserData($id,$db_con)));
    
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
