<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @package AppBundle\DependencyInjection
 * @see     http://symfony.com/doc/2.7/cookbook/bundles/extension.html
 */
class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        foreach ($config as $sectionKey => $sectionValue) {
            foreach ($sectionValue as $nodeKey => $nodeValue) {
                $container->setParameter($this->getAlias().'.'.$sectionKey.'.'.$nodeKey, $nodeValue);
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
