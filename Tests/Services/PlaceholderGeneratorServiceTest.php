<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\Services;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BernhardWebstudio\PlaceholderBundle\Tests\PlaceholderTest;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use BernhardWebstudio\PlaceholderBundle\Service\SqipPlaceholderGenerator;
use BernhardWebstudio\PlaceholderBundle\Service\PlaceholderGeneratorInterface;
use BernhardWebstudio\PlaceholderBundle\Service\PrimitivePlaceholderGenerator;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;

class PlaceholderGeneratorServiceTest extends TestCase
{
    /**
     * @var BernhardWebstudioPlaceholderExtension
     */
    protected $extension;
    /**
     * @var ContainerBuilder
     */
    protected $container;

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

    public function testSqipGenerator()
    {
        $this->loadConfiguration('sqip-test');
        $this->assertTrue($this->container->has('bewe_placeholder.generator'));
        $generator = $this->container->get('bewe_placeholder.generator');
        $this->assertInstanceof(SqipPlaceholderGenerator::class, $generator);
        $this->isGenerated($generator);
    }

    public function testPrimitiveGenerator()
    {
        $this->loadConfiguration('primitive-test');
        $this->assertTrue($this->container->has('bewe_placeholder.generator'));
        $generator = $this->container->get('bewe_placeholder.generator');
        $this->assertInstanceof(PrimitivePlaceholderGenerator::class, $generator);
        $this->isGenerated($generator);
    }

    public function isGenerated(PlaceholderGeneratorInterface $generator = null)
    {
        if ($generator) {
            $out = PlaceholderTest::TEST_IMAGE_OUTPUT . $generator->getOutputExtension();
            $this->assertFalse(\file_exists($out));
            $generator->generate(PlaceholderTest::TEST_IMAGE_INPUT, $out);
            \clearstatcache();
            $this->assertTrue(\file_exists($out));
            \unlink($out);
        }
    }
}
