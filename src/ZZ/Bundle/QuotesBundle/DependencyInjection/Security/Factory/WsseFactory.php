<?php

namespace ZZ\Bundle\QuotesBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class WsseFactory implements SecurityFactoryInterface
{
    private $kernelCacheDir = null;

    public function __construct($kernelCacheDir){
        $this->kernelCacheDir = $kernelCacheDir;
    }

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $dir_cache_nonce = $this->kernelCacheDir;

        if(!file_exists($dir_cache_nonce . $config['path_security_nonce'])){
            mkdir($dir_cache_nonce . $config['path_security_nonce'], 0770 );
        }

        $providerId = 'security.authentication.provider.wsse.' . $id;
        $container
            ->setDefinition(
                $providerId,
                new DefinitionDecorator('wsse.security.authentication.provider')
            )
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(1, $dir_cache_nonce . $config['path_security_nonce'])
            ->replaceArgument(2, $config['lifetime']);

        $listenerId = 'security.authentication.listener.wsse.' . $id;
        $listener = $container->setDefinition(
            $listenerId,
            new DefinitionDecorator('wsse.security.authentication.listener')
        );

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'wsse';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
            ->scalarNode('lifetime')->defaultValue(300)
            ->end();
        $node
            ->children()
            ->scalarNode('path_security_nonce')->defaultValue('/security')
            ->end();
    }
}