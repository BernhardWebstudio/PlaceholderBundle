<?php

namespace BernhardWebstudio\PlaceholderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration extends ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bewe_placeholder');

        $rootNode->children()
            ->integerNode('iterations')->defaultValue(5)->end()
            ->scalarNode('service')->cannotBeEmpty()->defaultValue('bewe.placeholder.primitive')->end()
            ->end();

        return $treeBuilder;
    }
}
