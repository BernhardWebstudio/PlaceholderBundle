<?php

namespace BernhardWebstudio\PlaceholderBundle\DependencyInjection;

use BernhardWebstudio\PlaceholderBundle\DependencyInjection\Configuration;
use BernhardWebstudio\PlaceholderBundle\Service\AbstractNodeExecGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BernhardWebstudioPlaceholderExtension extends Extension
{
    public function load(array $defaultConfigs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $defaultConfigs);
        $providerDefinition = $container->getDefinition('bewe_placeholder.provider');

        if (\array_key_exists('load_paths', $config)) {
            $providerDefinition->replaceArgument(1, $config['load_paths']);
        }

        if (\array_key_exists('output_path', $config)) {
            $output_path = $config['output_path'];
            if (!\file_exists($output_path)) {
                \mkdir($output_path, 0764, true);
            }
            $providerDefinition->replaceArgument(2, $output_path);
        }

        $service = $config['service'];
        $serviceDefinition = $container->getDefinition($config['service']);

        if (is_subclass_of($serviceDefinition->getClass(), AbstractNodeExecGenerator::class)) {
            if (\array_key_exists('bin', $config)) {
                $bin = $config['bin'];
                $serviceDefinition->replaceArgument(0, $bin);
            }

            if (\array_key_exists('node_bin', $config)) {
                $bin = $config['node_bin'];
                $serviceDefinition->replaceArgument(1, $bin);
            }

            if (\array_key_exists('iterations', $config)) {
                $iterations = $config['iterations'];
                $serviceDefinition->replaceArgument(2, $iterations);
            }
        }

        $container->setAlias('bewe_placeholder.generator', new Alias($service, true));
        $container->setParameter($this->getAlias(), $config);
    }

    public function getAlias()
    {
        return "bewe_placeholder";
    }
}
