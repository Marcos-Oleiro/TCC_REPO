<?php

use Oleiro\Data\DataHandler;
use Oleiro\DB\DBHandler;
use Oleiro\JWT\JWTHandler;
use Oleiro\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;

require dirname(__FILE__) . '/../libs/AuxFunc.php';

// Routes
$app->get('/hello/{name}', function ($request, $response, $args) {
    $string = "hello " . $args['name'];
    return $response->withJson($string);
});

$app->get('/games', function (Request $request, Response $response, array $args) {

    $stmt = $this->db->query("SELECT * FROM games");
    $games = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $games[] = [
            'id' => $row['id'],
            'name' => $row['name'],
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
    if (!Validator::checkEmptyFields($user_data)) {

        // verifica se os dados vieram com os campos necessários
        if (Validator::testFieldsNames($user_data)) {

            if (!Validator::validateNickname($user_data['nickname'])) { // se o nickname não passar no teste, vai entrar nesse if

                return $response->withStatus(400);
            } elseif (!Validator::validateEmail($user_data['email'])) { // se o email não passar no teste, vai entrar nesse if

                return $response->withStatus(400);
            } elseif (!Validator::validatePasswd($user_data['passwd'])) { // se o passwd não passar no teste, vai entrar nesse if

                return $response->withStatus(400);
            } else { // se passar em todos os testes, entra aqui

                if (!DBHandler::checkNewEmail($user_data['email'], $db_con)) {

                    $body = $response->getBody();

                    $body->write("Email já cadastrado, favor escolher outro");

                    return $response->withStatus(400);
                }
                if (!DBHandler::checkNewNickname($user_data['nickname'], $db_con)) {

                    $body = $response->getBody();

                    $body->write("Nickname já cadastrado, favor escolher outro");

                    return $response->withStatus(400);
                }

                $user_data['passwd'] = dbPass($user_data['passwd']);
                DBHandler::saveNewUser($user_data['nickname'], $user_data['email'], $user_data['passwd'], $db_con);
                return $response->withStatus(200)->write("ok!");
            }
        } else {
            return $response->withStatus(400);
        }
    } else {
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
    if (!Validator::checkEmptyFields($user_data)) {

        // verifica se os dados vieram com os campos necessários
        if (Validator::testFieldsNames($user_data)) {

            $db_data = DBHandler::checkUser($user_data['email'], dbPass($user_data['passwd']), $db_con);

            $token = JWTHandler::jwtBuilder($db_data);
            if (is_numeric($db_data)) { // se os dados estiverem corretos
                $response = $response->withHeader('id', DataHandler::idEncryptor($db_data))
                    ->withHeader("Access-Control-Expose-Headers", "id");

                //  transformar o objeto token em string para enviar para o cliente
                $message = array(
                    'token' => (string) $token,
                );

                $str_json = json_encode($message);
                // campos verificados, usuário verificado, token gerado
                // retorna token e http code 200 - OK
                return $response->withJson($str_json)->withStatus(200);

            } else { // dados não batendo com os dados do banco de dados
                $body = $response->getBody();
                $body->write($db_data);
                return $response->withStatus(400);

            }
        } else {
            // request inválida
            // http code 400 - bad request
            return $response->withStatus(400);
        }
    } else {
        // request inválida
        // http code 400 - bad request
        return $response->withStatus(400);
    }
});

// retorna as informações  necessárias do usuário com a id informada.
$app->get('/home/{id}', function (Request $request, Response $response, array $args) {

    // pega a id "encriptada" e transformma em ID numérico
    $id = DataHandler::idDecryptor($args['id']);

    // MXRlc3Rl

    $tkn_auth = $request->getHeader("HTTP_AUTHORIZATION")[0];

    // verifação do token
    // if (!verifyToken($tkn_auth, $id)) {
    if (!JWTHandler::verifyToken($tkn_auth, $id)) {

        return $response->withStatus(401);
    }

    // conexão do banco
    $db_con = $this->db;

    return $response->withJson(json_encode(DBHandler::getUserData($id, $db_con)));

});

// salva no banco de dados a nova descrição
$app->post('/profile/edit/desc/{id}', function (Request $request, Response $response, array $args) {

    $new_desc = $this->request->getParsedBody()['new_description'];

    $id = DataHandler::idDecryptor($args['id']);

    $tkn_auth = $request->getHeader("HTTP_AUTHORIZATION")[0];

    $db_con = $this->db;

    // verifação do token
    if (!JWTHandler::verifyToken($tkn_auth, $id)) {

        return $response->withStatus(401);
    }

    //  atualizaçã da descrição
    if (DBHandler::updateDescription($new_desc, $id, $db_con)) {

        return $response->withStatus(200);
    }

    return $response->withStatus(401);

});

// salva no banco de dados a nova senha
$app->post('/changepasswd/{id}', function (Request $request, Response $response, array $args) {

    $id = DataHandler::idDecryptor($args['id']);
    $current_passwd = $this->request->getParsedBody()['passwd'];
    $new_passwd = $this->request->getParsedBody()['new_passwd'];

    $tkn_auth = $request->getHeader("HTTP_AUTHORIZATION")[0];

    // verifação do token
    if (!JWTHandler::verifyToken($tkn_auth, $id)) {
        return $response->withStatus(401);
    }

    // var_dump(verifyToken($tkn_auth,$id));
    if ((Validator::validatePasswd($current_passwd) == 1) || (Validator::validatePasswd($new_passwd) == 1)) {

        $db_con = $this->db;

        if (DBHandler::checkPasswd($id, dbPass($current_passwd), $db_con)) {

            DBHandler::updatePasswd($id, dbPass($new_passwd), $db_con);
        } else {

            $body = $response->getBody();
            $body->write("Senha informada incorreta");
            return $response->withStatus(400);
        }
    } else {

        $body = $response->getBody();
        $body->write("Senha nao valida");
        return $response->withStatus(400);
    }

    return $response->withStatus(200);
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
