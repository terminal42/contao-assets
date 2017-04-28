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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('terminal42_assets');

        $rootNode
            ->children()
                ->scalarNode('root_dir')->isRequired()->end()
                ->arrayNode('packages')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->arrayNode('css')
                                ->requiresAtLeastOneElement()
                                ->prototype('array')
                                    ->beforeNormalization()
                                        ->ifString()
                                        ->then(function ($v) { return ['name' => $v]; })
                                    ->end()
                                    ->children()
                                        ->scalarNode('name')->isRequired()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('js')
                                ->requiresAtLeastOneElement()
                                ->prototype('array')
                                    ->beforeNormalization()
                                        ->ifString()
                                        ->then(function ($v) { return ['name' => $v]; })
                                    ->end()
                                    ->children()
                                        ->scalarNode('name')->isRequired()->end()
                                        ->scalarNode('section')->defaultValue('footer')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
