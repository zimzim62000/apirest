<?php

namespace ZZ\Bundle\QuotesBundle\Security\Authentication\Token;


use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WsseUserToken extends AbstractToken implements TokenInterface
{

    public $created;
    public $digest;
    public $nonce;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // Si l'utilisateur a des rôles, on le considère comme authentifié
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}