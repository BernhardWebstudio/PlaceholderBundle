<?php

namespace BernhardWebstudio\PlaceholderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    const ITERATIONS_DEFAULT = 5;

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bewe_placeholder');

        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('load_paths')
            ->scalarPrototype()->end()
            ->end()
            ->scalarNode('service')->cannotBeEmpty()->defaultValue('bewe_placeholder.generator.primitive')->end()
            ->integerNode('iterations')->defaultValue(self::ITERATIONS_DEFAULT)->end()
            ->scalarNode('bin')->end()
            ->scalarNode('node_bin')->end()
            ->scalarNode('output_path')->end()
            ->scalarNode('ignore_mtime')->defaultFalse()->end()
            ->end();

        return $treeBuilder;
    }
}
