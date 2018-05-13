<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BernhardWebstudio\PlaceholderBundle\Tests\PlaceholderTest;
use BernhardWebstudio\PlaceholderBundle\Twig\PlaceholderExtension;

class PlaceholderExtensionTest extends WebTestCase
{
    /**
     * @var PlaceholderExtension
     */
    protected $extension;

    protected function setUp()
    {
        self::bootKernel();
        $client = static::createClient();
        $container = $client->getContainer();

        $this->extension = new PlaceholderExtension($container->get("bewe_placeholder.provider"));
    }

    public function testGetFunctions()
    {
        $this->assertTrue(is_array($this->extension->getFunctions()));
        $this->assertEmpty($this->extension->getFunctions());

        $functions = $this->extension->getFunctions();
        foreach ($functions as $function) {
            $this->assertInstanceof(\Twig_SimpleFunction::class, $function);
        }
    }

    public function testGetFilters()
    {
        $this->assertTrue(is_array($this->extension->getFilters()));
        $this->assertNotEmpty($this->extension->getFilters());

        $filters = $this->extension->getFilters();
        foreach ($filters as $filter) {
            $this->assertInstanceof(\Twig_SimpleFilter::class, $filter);
        }
        $this->assertEquals('placeholder', $filters[0]->getName());
    }

    public function testGetPlaceholder() {
        $out = PlaceholderTest::TEST_IMAGE_OUTPUT . ".svg";
        $path = $this->extension->getPlaceholder(PlaceholderTest::TEST_IMAGE_INPUT, 'svg');
        $this->assertEquals($path, $out);
        $base64 = $this->extension->getPlaceholder(PlaceholderTest::TEST_IMAGE_INPUT, 'base64');
        $this->assertTrue(file_exists($out));
        $this->assertEquals(\base64_encode(\file_get_contents($out)), $base64);
        unlink($out);
    }
}
