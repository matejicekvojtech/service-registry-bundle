<?php

namespace MV\ServiceRegistryBundle;

use MV\ServiceRegistryBundle\DependencyInjection\ServiceRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ServiceRegistryBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ServiceRegistryPass());
    }
}
