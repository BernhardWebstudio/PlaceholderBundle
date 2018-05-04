<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use BernhardWebstudio\PlaceholderBundle\Service\SqipPlaceholderGenerator;
use BernhardWebstudio\PlaceholderBundle\Service\PrimitivePlaceholderGenerator;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;

class PlaceholderGeneratorServiceTest extends TestCase
{
    /**
     * @var BernhardWebstudioPlaceholderExtension
     */
    private $extension;
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     *
     */
    protected function setUp()
    {
        $this->extension = new BernhardWebstudioPlaceholderExtension();

        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
    }

    /**
     * @param string           $resource
     */
    protected function loadConfiguration($resource)
    {
        // reset configuration
        $this->setUp();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../DependencyInjection/Fixtures/'));
        $loader->load($resource . '.yml');
        $this->container->compile();
    }

    public function testSqipGenerator() {
        $this->loadConfiguration('sqip-test');
        $this->assertTrue($this->container->has('bewe_placeholder.generator'));
        $generator = $this->container->get('bewe_placeholder.generator');
        $this->assertTrue($generator instanceof SqipPlaceholderGenerator);

        // TODO: test return value of ->generate
    }

    public function testPrimitiveGenerator() {
        $this->loadConfiguration('primitive-test');
        $this->assertTrue($this->container->has('bewe_placeholder.generator'));
        $generator = $this->container->get('bewe_placeholder.generator');
        $this->assertTrue($generator instanceof PrimitivePlaceholderGenerator);

        // TODO: test return value of ->generate
    }
}
