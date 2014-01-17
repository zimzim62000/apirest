<?php

namespace ZZ\Bundle\QuotesBundle;

use ZZ\Bundle\QuotesBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ZZQuotesBundle extends Bundle
{
    public function build(ContainerBuilder $container)
{
    parent::build($container);

    $extension = $container->getExtension('security');
    $extension->addSecurityListenerFactory(new WsseFactory($container->getParameter('kernel.cache_dir')));
}
}