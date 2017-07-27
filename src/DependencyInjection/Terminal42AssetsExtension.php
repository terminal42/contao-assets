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
     * @var Filesystem
     */
    private $filesystem;

    private $rootDir;
    private $webDir;

    private static $configKeys = ['version', 'section'];

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('listener.yml');

        $collections = $this->parseCollections(
            $mergedConfig['collections'],
            $mergedConfig['root_dir'],
            $container->getParameter('kernel.root_dir').'/../web'
        );

        $container
            ->getDefinition('terminal42_assets.listener.assets')
            ->setArgument(0, $collections)
        ;
    }

    /**
     * Parse the collections by computing the file version.
     *
     * @param array  $collections
     * @param string $rootDir
     * @param string $webDir
     *
     * @return array
     */
    private function parseCollections(array $collections, string $rootDir, string $webDir): array
    {
        $this->filesystem = new Filesystem();
        $this->rootDir = $rootDir;
        $this->webDir = $webDir;

        foreach ($collections as &$collection) {
            $collection['meta'] = $this->parseTags($collection['meta']);
            $collection['link'] = $this->parseTags($collection['link'], 'href');
            $collection['script'] = $this->parseTags($collection['script'], 'src');
        }

        return $collections;
    }

    private function parseTags(array $configs, string $pathKey = null)
    {
        $keys = array_flip(static::$configKeys);

        foreach ($configs as &$config) {
            $attributes = array_diff_key($config, $keys);
            $config = array_intersect_key($config, $keys);

            if (null !== $pathKey && !$this->isAbsoluteUrl($attributes[$pathKey])) {
                $path = Path::canonicalize($this->rootDir.'/'. $attributes[$pathKey]);
                $this->addFileInfo($config, $attributes, $path);
                $attributes[$pathKey] = Path::makeRelative($path, $this->webDir);
            }

            $config['attributes'] = $attributes;
        }

        return $configs;
    }

    private function addFileInfo(array &$config, array &$attributes, string $path)
    {
        if (!isset($attributes['integrity'])) {
            $attributes['integrity'] = 'sha256-'.base64_encode(hash_file('sha256', $path));
        }

        if (!isset($config['version'])) {
            list(,$hash) = explode('-', $attributes['integrity']);

            $config['version'] = substr(md5(base64_decode($hash)), 0, 8);
        }
    }

    private function isAbsoluteUrl(string $path)
    {
        return preg_match('{^(https?:)?//}is', $path);
    }
}
