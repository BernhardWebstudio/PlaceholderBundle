<?php

namespace BernhardWebstudio\PlaceholderBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\Configuration;
use BernhardWebstudio\PlaceholderBundle\Service\AbstractNodeExecGenerator;

class BernhardWebstudioPlaceholderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $service = $config['service'];
        $providerDefinition = $container->getDefinition('bewe.placeholder.provider');
        $providerDefinition->replaceArgument(0, $service);
        $serviceDefinition = $container->getDefinition($service);
        
        if (is_subclass_of($serviceDefinition->getClass(), AbstractNodeExecGenerator::class)) {
            if (($bin = $configs['bin'])) {
                $serviceDefinition->replaceArgument(0, $bin);
            }

            if (($iterations = $configs['iterations'])) {
                $serviceDefinition->replaceArgument(1, $iterations);
            }
        }
        
    }
}
