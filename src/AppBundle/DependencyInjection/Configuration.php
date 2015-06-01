<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @package AppBundle\DependencyInjection
 * @see     http://symfony.com/doc/2.7/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('app');

        $rootNode
            ->children()
                ->arrayNode('maintenance')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('opcache')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('secret')
                                    ->cannotBeEmpty()
                                    ->defaultValue('ThisKeyIsNotSecret')
                                ->end()// secret
                                ->scalarNode('host_ip')
                                    ->cannotBeEmpty()
                                    ->defaultValue('127.0.0.1:80')
                                ->end()// host_ip
                                ->scalarNode('host_name')
                                    ->cannotBeEmpty()
                                    ->defaultValue('acme.com')
                                ->end()// host_name
                                ->enumNode('protocol')
                                    ->cannotBeEmpty()
                                    ->values(array('http', 'https'))
                                    ->defaultValue('http')
                                ->end()// protocol
                                ->scalarNode('web_dir')
                                    ->cannotBeEmpty()
                                    ->defaultValue('%kernel.root_dir%/../web')
                                ->end()// web_dir
                            ->end()
                        ->end() // opcache
                    ->end()
                ->end() // maintenance
                ->arrayNode('request_context')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')
                            ->cannotBeEmpty()
                            ->defaultValue('acme.com')
                        ->end()// host
                        ->enumNode('scheme')
                            ->cannotBeEmpty()
                            ->values(array('http', 'https'))
                            ->defaultValue('http')
                        ->end()// scheme
                    ->end()
                ->end() // request_context
            ->end();

        return $treeBuilder;
    }
}
