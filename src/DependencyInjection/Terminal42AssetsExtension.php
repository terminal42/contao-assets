<?php

declare(strict_types=1);

/*
 * Assets Bundle for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2017-2017, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-assets
 */

namespace Terminal42\AssetsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Webmozart\PathUtil\Path;

class Terminal42AssetsExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('listener.yml');
        $loader->load('services.yml');

        $definition = $container->getDefinition('terminal42_assets.registry');
        $definition->addArgument($this->parsePackages($mergedConfig['packages'], $mergedConfig['root_dir']));
    }

    /**
     * Parse the packages by computing the file version.
     *
     * @param array  $packages
     * @param string $rootDir
     *
     * @return array
     */
    private function parsePackages(array $packages, string $rootDir): array
    {
        $fs = new Filesystem();

        foreach ($packages as &$package) {
            $package['css'] = $this->computeFileVersions($package['css'], $rootDir, $fs);
            $package['js'] = $this->computeFileVersions($package['js'], $rootDir, $fs);
        }

        return $packages;
    }

    /**
     * Compute the file versions.
     *
     * @param array      $files
     * @param string     $rootDir
     * @param Filesystem $fs
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    private function computeFileVersions(array $files, string $rootDir, Filesystem $fs): array
    {
        foreach ($files as &$file) {
            $path = Path::canonicalize($rootDir.'/'.$file['name']);

            if (!$fs->exists($path)) {
                throw new \RuntimeException(sprintf('The file "%s" does not exist', $path));
            }

            $file['name'] = $path;
            $file['version'] = md5_file($path);
        }

        return $files;
    }
}
