<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\DependencyInjection;

use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class BernhardWebstudioPlaceholderExtensionTest
 *
 * @package BernhardWebstudio\PlaceholderBundle\DependencyInjection
 */
class BernhardWebstudioPlaceholderExtensionTest extends TestCase
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
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/Fixtures/'));
        $loader->load($resource . '.yml');
        $this->container->compile();
    }

    /**
     * Test empty config
     */
    public function testWithoutConfiguration()
    {
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();
        $this->assertTrue($this->container->hasParameter('bewe_placeholder'));
        $config = $this->container->getParameter('bewe_placeholder');
        $this->assertFalse(isset($config['bin']));
        $this->assertTrue($config['iterations'] == Configuration::ITERATIONS_DEFAULT);
        $this->assertTrue($config['service'] == 'bewe_placeholder.generator.primitive');
    }

    /**
     * Test sqip config
     */
    public function testSqipConfig()
    {
        $this->loadConfiguration('sqip-test');
        $this->assertTrue($this->container->hasParameter('bewe_placeholder'));
        $config = $this->container->getParameter('bewe_placeholder');
        $this->assertNotEmpty($config['bin']);
        $this->assertEquals($config['bin'], 'sqip');
        $this->assertNotEmpty($config['service']);
        $this->assertEquals($config['service'], 'bewe_placeholder.generator.sqip');
        $this->assertNotEmpty($config['iterations']);
        $this->assertEquals($config['iterations'], 13);
    }

    /**
     * Test primitive config
     */
    public function testPrimitiveConfig()
    {
        $this->loadConfiguration('primitive-test');
        $this->assertTrue($this->container->hasParameter('bewe_placeholder'));
        $config = $this->container->getParameter('bewe_placeholder');
        $this->assertNotEmpty($config['bin']);
        $this->assertEquals($config['bin'], 'primitive');
        $this->assertNotEmpty($config['service']);
        $this->assertEquals($config['service'], 'bewe_placeholder.generator.primitive');
        $this->assertNotEmpty($config['iterations']);
        $this->assertEquals($config['iterations'], 13);
    }

    /**
     *
     */
    public function testGetAlias()
    {
        $this->assertEquals($this->extension->getAlias(), 'bewe_placeholder');
    }
}
