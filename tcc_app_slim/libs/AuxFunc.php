<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Parser;

// só checa se array tem somente 3 campos e se os campos não estão vazios, retorna false se não tem campos vazios e true se tiver campos vazios
function checkEmptyFields($user_data){

    $user_data_values = array_values($user_data);
    
    if ( count($user_data_values) != 3 ){
        return true;
    } 
    elseif ( (strlen($user_data_values[0]) == 0 ) || (strlen($user_data_values[1]) == 0 ) || (strlen($user_data_values[2]) == 0 ) ) {
        return true;
    }

    return false;
}

// Função que verifica se os campos possuem os nomes corretos...retorna true se tiverem ou false se não tiverem
function testFieldsNames ($user_data) {
    if ( (array_key_exists('nickname',$user_data) == false ) || (array_key_exists('email',$user_data) == false ) || (array_key_exists('passwd',$user_data) == false )  ){
        return false;
    } 
    return true;
}

// Função para validar o nickname, retorna true se os campos estiverem ok
function validateNickname ($nickname){
    
    $pattern_nickaname = "/([A-Z]*|[a-z]*|[0-9]*|[\-_\$]*)^.{4,10}/";
    return validateReGex($pattern_nickaname, $nickname);
}

// Função para validar o email, retorna true se os campos estiverem ok
function validateEmail ($email){
    
    $pattern_email = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
    return validateReGex($pattern_email, $email);
}

// Função para validar o passwd, retorna true se os campos estiverem ok
function validatePasswd ($passwd){

    $pattern_passwd = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,10}$/";
    return validateReGex($pattern_passwd, $passwd);
}

// Função que testa um campo dado utilizando uma regex informada.
function validateReGex ($pattern, $field){
    return (preg_match($pattern, $field));
}

// Verifica se o campo e-mail já é cadastrado no banco de dados. 
function checkNewEmail ($email, $db_con){
    
    $stmt = $db_con->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email',$email);
    $stmt->execute();
    $row = $stmt->fetch(); // se não for encontrado resultado, o fetch retorna false

    if ( ($row == false) ) {
        return true; // true indica que o usuário é novo
    }
    return false;
}

// Verifica se o campo nickname já é cadastrado no banco de dados. 
function checkNewNickname($nickname, $db_con) {

    $stmt = $db_con->prepare("SELECT * FROM users WHERE nickname = :nickname");
    $stmt->bindParam(':nickname', $nickname);
    $stmt->execute();
    $row = $stmt->fetch(); // se não for encontrado resultado, o fetch retorna false

    if (($row == false)) {
        return true; // true indica que o usuário é novo
    }

    return false;
}

// função que salva no banco de dados o usuário recém registrado
function saveNewUser ($nickname, $email, $passwd, $db_con){
    $stmt = $db_con->prepare ("INSERT INTO users (nickname, email,passwd) VALUES(:nickname,:email,:passwd)");
    $stmt->bindParam(':nickname', $nickname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':passwd', $passwd);
    $stmt->execute();
}
// função devolve o password que vai ser salvo no banco de dados
function dbPass ($passwd) {
    return hash('sha256',$passwd . 'nirvana' );
}

// função que transforma a id do usuário para base64 ⁽concatenada com uma string), para ser enviada para o cliente
function idEncryptor($id) {
    return base64_encode($id.'teste');

}
// função que pega a id, em base 64, e devolve a id "limpa" para processamento
function idDecryptor($str){

    $str_crpt = base64_decode($str);

    // tamanho da string segredo utilizada para criar a string em base64
    // quando for pro servidor, utilizar variável de ambiente (?) e pegar o seu tamanho
    // MUDAR PARA VARIÁVEL DE AMBIENTE DEPOIS!!!!
    $size_secret = 5;
    // -----------------------------------
        
    return substr( $str_crpt , 0 , (strlen($str_crpt)) - $size_secret );
    
}
// função que verifica as credenciais do usuário
function checkUser ($email, $passwd, $db_con) { 

    $stmt = $db_con->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email' , $email);
    $stmt->execute();
    $row = $stmt->fetch();
    // return "olá";
    // return $passwd;
    if ( $row != false ){
        if ($row['passwd'] ==  $passwd){
            // return print_r($row);
            return $row['id'];
        }
        else{
            return 'E-mail ou Senha incorretos';
        }
        
    }
    else {
        return 'Dados não cadastrados';
    }

}

// function logIn(){
//     $_SESSION['logged'] = true;
// }
// function logOff(){
//     $_SESSION['logged'] = false;
// }

// function isLogged(){
//     return $_SESSION['logged'];
// }

function getUserData($id, $db_con){

    $stmt = $db_con->prepare("SELECT photography, nickname, description FROM users WHERE id = :id");
    $stmt->bindParam(':id' , $id);
    $stmt->execute();
    $row = $stmt->fetch();
    // return $id;  
    return $row;

}

function jwtBuilder ($user_id){



    $signer = new Sha512();

    $token = (new Builder())->setIssuer('http://oleirosoftware.com') // Configures the issuer (iss claim)
                        ->setAudience('http://oleirosoftware.org') // Configures the audience (aud claim)
                        ->setIssuedAt(time()) // Configures the time that the token was issued (iat claim)
                        // ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
                        ->setSubject($user_id)
                        ->sign($signer, $_ENV["JWT_KEY"]) // creates a signature using "testing" as key
                        ->getToken(); // Retrieves the generated token


    // print_r($token->getHeaders()); // Retrieves the token headers
    // print_r($token); // Retrieves the token headers
    // $token->getClaims(); // Retrieves the token claims


    // echo ('Issuer Claim : ' . $token->getClaim('iss')) . PHP_EOL;
    // echo ('Aud Clam : ' . $token->getClaim('aud')) . PHP_EOL;
    // echo ('Sub Clam : ' . $token->getClaim('sub')) . PHP_EOL;
    // echo "Gerado em: " .date('d/M/Y H:i:s',$token->getClaim('iat'))."\n";    
    // echo "Expira em: " .date('d/M/Y H:i:s',$token->getClaim('exp'))."\n";
    

    // srand(random_int (PHP_INT_MIN,PHP_INT_MAX)/time());
    // echo rand();
    // echo dechex(rand());
    // echo base64_encode(random_int (PHP_INT_MIN,PHP_INT_MAX)/time());

    // print_r($token);
    // echo $_ENV["JWT_KEY"];
    // var_dump($token->verify($signer, $_ENV["JWT_KEY"]));
    // var_dump($token->verify($signer, 'blsblsblsbls'));
    return $token;
 }

 /*
    Um token é gerado para durar 3 horas (?)
    O token pode ser renovado (como?)
    Fazer uma função que possa invalidar o token quando o cliente solicitar (como?)
 */

 function validateToken ($str_token){

     $signer = new Sha512();
     
    // tranformar a String do token em Obj
    $token = (new Parser())->parse((string) $str_token);
    
    return ($token->verify($signer,$_ENV['JWT_KEY']));
 }