<?php

namespace BernhardWebstudio\PlaceholderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    const ITERATIONS_DEFAULT = 5;

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bewe_placeholder');

        $rootNode->children()
            ->integerNode('iterations')->defaultValue(self::ITERATIONS_DEFAULT)->end()
            ->scalarNode('bin')->end()
            ->scalarNode('service')->cannotBeEmpty()->defaultValue('bewe_placeholder.generator.primitive')->end()
            ->end();

        return $treeBuilder;
    }
}
