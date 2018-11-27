<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JWTBuilder {

    public function Builder (){
        

    $signer = new Sha256();
    $keychain = new Keychain();


    // issuer       -> quem gerou o jwt
    // audience     -> para quem o jwt pode enviado (?)
    // id           ->id único da claim
    // 
    
    $token = (new Builder())->setIssuer("") // localhost ?
                            ->setAudience("") // url que eu quero acessar?
                            ->setId()  // id gerada por mim
                            ->setIssuedAt(time())
                            ->setNotBefore(time() + 60)
                            ->setExperation(time() + 3600)
                            ->set()
                            ->sign($signer,$keychain->getPrivateKey('file://{pathToFile}'))
                            ->getToken();

    var_dump($token->verify($signer,$keychain->getPublicKey('file://{pathToFile}')));

    }                   
}
?>