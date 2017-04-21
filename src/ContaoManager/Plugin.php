<?php

declare(strict_types = 1);

namespace Terminal42\AssetsBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Terminal42\AssetsBundle\Terminal42AssetsBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * @inheritdoc
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(Terminal42AssetsBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
