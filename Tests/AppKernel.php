<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests;

// use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
// use Symfony\Component\HttpKernel\Kernel as BaseKernel;

// class AppKernel extends BaseKernel
// {
// 	use MicroKernelTrait;
// }


use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;


use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use Symfony\Component\Routing\RouteCollection;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BernhardWebstudio\PlaceholderBundle\BernhardWebstudioPlaceholderBundle;

/**
 * Class AppKernel
 * It is needed to simulate an application to make some functional tests
 */
class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * @var string[]
     */
    private $bundlesToRegister = [];

    /**
     * @var array
     */
    private $configFiles = [];

    /**
     * @var string
     */
    private $cachePrefix = '';

    public function __construct($cachePrefix = 'test')
    {
        parent::__construct($cachePrefix, true);
        $this->cachePrefix = $cachePrefix;
        $this->addBundle(FrameworkBundle::class);
        $this->addBundle(TwigBundle::class);
        $this->addBundle(BernhardWebstudioPlaceholderBundle::class);
        $this->addConfigFile(__DIR__ . '/config.yaml');
        $this->addConfigFile(__DIR__ . '/services.yaml');
        $this->addConfigFile(__DIR__ . '/../src/Resources/config/services.yaml');
    }

    /**
     * @param string $bundleClassName class name of bundle
     */
    public function addBundle($bundleClassName)
    {
        $this->bundlesToRegister[] = $bundleClassName;
    }

    /**
     * @param string $configFile path to config file
     */
    public function addConfigFile($configFile)
    {
        $this->configFiles[] = $configFile;
    }

    public function registerBundles(): iterable
    {
        $this->bundlesToRegister = array_unique($this->bundlesToRegister);
        $bundles = [];
        foreach ($this->bundlesToRegister as $bundle) {
            $bundles[] = new $bundle();
        }
        return $bundles;
    }


    /**
     * Adds or imports routes into your application.
     *
     *     $routes->import($this->getConfigDir().'/*.{yaml,php}');
     *     $routes
     *         ->add('admin_dashboard', '/admin')
     *         ->controller('App\Controller\AdminController::dashboard')
     *     ;
     */
    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->getConfigDir() . '/routes.yaml');
    }

    /**
     * Configures the container.
     *
     * You can register extensions:
     *
     *     $container->extension('framework', [
     *         'secret' => '%secret%'
     *     ]);
     *
     * Or services:
     *
     *     $container->services()->set('halloween', 'FooBundle\HalloweenProvider');
     *
     * Or parameters:
     *
     *     $container->parameters()->set('halloween', 'lot of fun');
     */
    protected function configureContainer(ContainerConfigurator $container, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yaml');

        $configDir = $this->getConfigDir();

        $container->import($configDir . '/{packages}/*.{php,yaml}');
        $container->import($configDir . '/{packages}/' . $this->environment . '/*.{php,yaml}');

        if (is_file($configDir . '/services.yaml')) {
            $container->import($configDir . '/services.yaml', 'yaml', false);
            $loader->load($configDir . '/services.yaml', 'yaml', false);
            $container->import($configDir . '/{services}_' . $this->environment . '.yaml');
        } else {
            $container->import($configDir . '/{services}.php');
        }
    }

    /**
     * Gets the path to the configuration directory.
     */
    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }

    public function getProjectDir(): string
    {
        return __DIR__ . '';
    }

    public function getLogDir(): string
    {
        return $this->getProjectDir() . '/var/log';
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir() . '/var/cache';
    }
}
