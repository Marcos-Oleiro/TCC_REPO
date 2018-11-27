<?php
use Auth0\SDK\JWTVerifier;

namespace App;

class Main {

    protected $token;
    protected $tokenInfo;



    public function setCurrentToken ($token){

        try{

            $verifier = new JWTVerifier([
                'suported_algs' => ['RS256'],
                'valid_audiences' => ['teste de API'],
                'authorized_iss' => ['https://oleirosoftware.auth0.com/']
            ]);

            $this->token  = $token;

            $this->tokenInfo = $verifier->verifyAndDecode($token);
        }

        catch( \Auth0\SDK\Exception\CoreException $e){
            throw $e;
        }
    }

    public function publicEndPoint (){
        return array (
            "status" => "ok",
            "message" => "Rota Pública"
        );
    }


    public function privateEndPoint (){
        return array (
            "status" => "ok",
            "message" => "Rota Privada"
        );
    }
}


?>
