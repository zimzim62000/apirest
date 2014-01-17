<?php

namespace ZZ\Bundle\QuotesBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use ZZ\Bundle\QuotesBundle\Security\Authentication\Token\WsseUserToken;

class WsseProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;
    private $lifetime;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $lifetime)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir = $cacheDir;
        $this->lifetime = $lifetime;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if ($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword())) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    protected function validateDigest($digest, $nonce, $created, $secret)
    {
        // Expire le timestamp après config lifetime
        if (time() - strtotime($created) > $this->lifetime) {
            return false;
        }

        // Valide que le nonce est unique dans le config lifetime
        if (file_exists($this->cacheDir . '/' . $nonce) && file_get_contents(
                $this->cacheDir . '/' . $nonce
            ) + $this->lifetime > time()
        ) {
            throw new NonceExpiredException('Previously used nonce detected');
        }

        file_put_contents($this->cacheDir . '/' . $nonce, time());

        $expected = base64_encode(sha1(base64_decode($nonce) . $created . $secret, true));

        return $digest === $expected;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}