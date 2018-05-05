<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\Controller;

use PlaceholderGeneratorServiceTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BernhardWebstudio\PlaceholderBundle\Tests\AppKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;

class PlaceholderProviderControllerTest extends WebTestCase
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
     * @var TestClient
     */
    private $client;

    /**
     *
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    /**
     * Test that a 404 is thrown when requesting a non-existent image
     */
    public function testPlaceholderUnavailableAction()
    {
        // $this->expectException(NotFoundHttpException::class);
        try {
            $this->client->request('GET', '/non-exisitiniging.jpg/placeholder');
            $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof NotFoundHttpException);
        }
    }

    /**
     * Test that a valid response is returned upon requesting an exisiting image
     */
    public function testPlaceholderAvailableAction()
    {
        $this->client->request('GET', PlaceholderGeneratorServiceTest::TEST_IMAGE_INPUT . "/placeholder");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(\file_exists(PlaceholderGeneratorServiceTest::TEST_IMAGE_OUTPUT . '.svg'));
        unlink(PlaceholderGeneratorServiceTest::TEST_IMAGE_OUTPUT . '.svg');
    }

    public static function getKernelClass()
    {
        return AppKernel::class;
    }
}
