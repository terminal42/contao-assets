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

        $container->setParameter('terminal42_assets.root_dir', $mergedConfig['root_dir']);
        $container->setParameter(
            'terminal42_assets.collections',
            $this->parseCollections($mergedConfig['collections'], $mergedConfig['root_dir'])
        );
    }

    /**
     * Parse the collections by computing the file version.
     *
     * @param array  $collections
     * @param string $rootDir
     *
     * @return array
     */
    private function parseCollections(array $collections, string $rootDir): array
    {
        $fs = new Filesystem();

        foreach ($collections as &$collection) {
            $collection['css'] = $this->computeFileVersions($collection['css'], $rootDir, $fs);
            $collection['js'] = $this->computeFileVersions($collection['js'], $rootDir, $fs);
        }

        return $collections;
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
