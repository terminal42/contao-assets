<?php

declare(strict_types = 1);

namespace Terminal42\AssetsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class Terminal42AssetsExtension extends ConfigurableExtension
{
    /**
     * @inheritDoc
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('listener.yml');
        $loader->load('services.yml');

        $container->setParameter('terminal42_assets.root_dir', $mergedConfig['root_dir']);
        $container->setParameter('terminal42_assets.packages', $mergedConfig['packages']);
    }
}
