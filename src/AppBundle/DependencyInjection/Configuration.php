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
            ->isRequired()
            ->children()
                ->arrayNode('maintenance')
                    ->addDefaultsIfNotSet()
                    ->isRequired()
                    ->children()
                        ->arrayNode('opcache')
                            ->addDefaultsIfNotSet()
                            ->isRequired()
                            ->children()
                                ->scalarNode('secret')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->defaultValue('ThisKeyIsNotSecret')
                                ->end()// secret
                                ->scalarNode('host_ip')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->defaultValue('127.0.0.1:80')
                                ->end()// host_ip
                                ->scalarNode('host_name')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->defaultValue('acme.com')
                                ->end()// host_name
                                ->scalarNode('web_dir')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->defaultValue('%kernel.root_dir%/../web')
                                ->end()// web_dir
                                ->enumNode('protocol')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                    ->values(array('http', 'https'))
                                    ->defaultValue('http')
                                ->end()// protocol
                            ->end()
                        ->end() // opcache
                    ->end()
                ->end() // maintenance
            ->end();

        return $treeBuilder;
    }
}
