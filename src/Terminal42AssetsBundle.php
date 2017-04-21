<?php

declare(strict_types = 1);

namespace Terminal42\AssetsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Terminal42\AssetsBundle\DependencyInjection\Compiler\PackagePass;

class Terminal42AssetsBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PackagePass('terminal42_assets'));
    }
}
