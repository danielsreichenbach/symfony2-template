<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author Daniel S. Reichenbach <daniel@kogitoapp.com>
 * @see    http://symfony.com/doc/2.7/cookbook/bundles/extension.html
 */
class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        foreach ($config as $sectionKey => $sectionValue) {
            $container->setParameter($this->getAlias().'.'.$sectionKey, $sectionValue);

            if (is_array($sectionValue)) {
                foreach ($sectionValue as $nodeKey => $nodeValue) {
                    $container->setParameter($this->getAlias().'.'.$sectionKey.'.'.$nodeKey, $nodeValue);
                    if (is_array($nodeValue)) {
                        foreach ($nodeValue as $subNodeKey => $subNodeValue) {
                            $container->setParameter(
                                $this->getAlias().'.'.$sectionKey.'.'.$nodeKey.'.'.$subNodeKey,
                                $subNodeValue
                            );
                        }
                    }
                }
            }
        }

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
