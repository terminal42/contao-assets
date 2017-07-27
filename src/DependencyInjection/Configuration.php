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
                // TODO replace with %kernel.project_dir% in Symfony 3.3
                ->scalarNode('root_dir')->defaultValue('%kernel.root_dir%/../web')->end()
                ->arrayNode('collections')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')
                                ->validate()
                                    ->always(function ($value) {
                                        if (strlen($value) > 128) {
                                            throw new \InvalidArgumentException('A collection name must be no longer than 128 chars.');
                                        }

                                        return $value;
                                    })
                                ->end()
                            ->end()
                            ->arrayNode('link')
                                ->prototype('array')
                                    ->beforeNormalization()
                                        ->ifString()
                                        ->then(function ($v) { return ['href' => $v]; })
                                    ->end()
                                    ->children()
                                        ->scalarNode('href')->isRequired()->info('This attribute specifies the URL of the linked resource. A URL might be absolute or relative.')->end()
                                        ->scalarNode('rel')->defaultValue('stylesheet')->info('This attribute names a relationship of the linked document to the current document. The attribute must be a space-separated list of the link types values. The most common use of this attribute is to specify a link to an external style sheet: the rel attribute is set to stylesheet, and the href attribute is set to the URL of an external style sheet to format the page.')->end()
                                        ->scalarNode('type')->info('This attribute is used to define the type of the content linked to. The value of the attribute should be a MIME type such as text/html, text/css, and so on.')->end()
                                        ->scalarNode('media')->info('This attribute specifies the media which the linked resource applies to. Its value must be a media query. ')->end()
                                        ->scalarNode('integrity')->info('Contains inline metadata, a base64-encoded cryptographic hash of a resource (file) you’re telling the browser to fetch, that a user agent can use to verify that a fetched resource has been delivered free of unexpected manipulation.')->end()
                                        ->scalarNode('crossorigin')->info('This enumerated attribute indicates whether CORS must be used when fetching the related image.')->end()
                                        ->scalarNode('hreflang')->info('This attribute indicates the language of the linked resource. Allowed values are determined by BCP47.')->end()
                                        ->scalarNode('prefetch')->info('This attribute identifies a resource that might be required by the next navigation and that the user agent should retrieve it.')->end()
                                        ->scalarNode('refererpolicy')->info('A string indicating which referrer to use when fetching the resource')->end()
                                        ->scalarNode('sizes')->info('This attribute defines the sizes of the icons for visual media contained in the resource. It must be present only if the rel contains the icon link types value.')->end()
                                        ->scalarNode('version')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('script')
                                ->prototype('array')
                                    ->beforeNormalization()
                                        ->ifString()
                                        ->then(function ($v) { return ['src' => $v]; })
                                    ->end()
                                    ->children()
                                        ->scalarNode('src')->info('This attribute specifies the URI of an external script.')->isRequired()->end()
                                        ->scalarNode('section')->defaultValue('footer')->end()
                                        ->booleanNode('async')->defaultFalse()->info('Set this Boolean attribute to indicate that the browser should, if possible, execute the script asynchronously.')->end()
                                        ->scalarNode('crossorigin')->end()
                                        ->scalarNode('integrity')->info('Contains inline metadata that a user agent can use to verify that a fetched resource has been delivered free of unexpected manipulation.')->end()
                                        ->booleanNode('defer')->defaultFalse()->info('This Boolean attribute is set to indicate to a browser that the script is meant to be executed after the document has been parsed, but before firing DOMContentLoaded.')->end()
                                        ->scalarNode('type')->info('This attribute identifies the scripting language of code embedded within a script element or referenced via the element’s src attribute.')->end()
                                        ->scalarNode('version')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('meta')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->end()
                                        ->scalarNode('property')->end()
                                        ->scalarNode('charset')->end()
                                        ->scalarNode('content')->end()
                                        ->scalarNode('http-equiv')->end()
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
