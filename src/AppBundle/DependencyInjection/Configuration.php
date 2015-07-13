<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 * @see    http://symfony.com/doc/2.7/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class
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
                                    ->isRequired()
                                    ->defaultValue('ThisKeyIsNotSecret')
                                ->end()// secret
                                ->scalarNode('host_ip')
                                    ->isRequired()
                                    ->defaultValue('127.0.0.1:80')
                                ->end()// host_ip
                                ->scalarNode('host_name')
                                    ->isRequired()
                                    ->defaultValue('acme.com')
                                ->end()// host_name
                                ->scalarNode('protocol')
                                    ->defaultValue('http')
                                    ->validate()
                                          ->ifNotInArray(array('http', 'https'))
                                          ->thenInvalid('The connection protocol must be either \'http\' or \'https\'.')
                                    ->end()
                                ->end() // protocol
                                ->scalarNode('web_dir')
                                    ->defaultValue('%kernel.root_dir%/../web')
                                ->end()// web_dir
                            ->end()
                        ->end() // opcache
                    ->end()
                ->end() // maintenance
                ->arrayNode('users')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_group')
                            ->isRequired()
                            ->defaultValue('Users')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('request_context')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')
                            ->isRequired()
                            ->defaultValue('acme.com')
                        ->end()// host
                        ->scalarNode('protocol')
                            ->defaultValue('http')
                            ->validate()
                                  ->ifNotInArray(array('http', 'https'))
                                  ->thenInvalid('The connection protocol must be either \'http\' or \'https\'.')
                            ->end()
                        ->end() // protocol
                    ->end()
                ->end() // request_context
                ->arrayNode('google_analytics')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($propertyId) {
                                return array('property_id' => $propertyId);
                            })
                        ->end()
                        ->children()
                            ->scalarNode('property_id')
                                ->isRequired()
                            ->end()
                            ->booleanNode('require_display_features')
                                ->defaultFalse()
                            ->end()
                        ->end()
                    ->end()
                ->end() // google_analytics
            ->end();

        return $treeBuilder;
    }
}
