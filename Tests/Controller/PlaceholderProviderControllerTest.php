<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use BernhardWebstudio\PlaceholderBundle\Tests\AppKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BernhardWebstudio\PlaceholderBundle\Tests\PlaceholderTest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;

class PlaceholderProviderControllerTest extends WebTestCase
{
    /**
     * @var BernhardWebstudioPlaceholderExtension
     */
    protected $extension;
    /**
     * @var ContainerBuilder
     */
    protected $localContainer;
    /**
     * @var TestClient
     */
    protected $client;

    // public static function getKernelClass(): string
    // {
    //     return AppKernel::class;
    // }

    /**
     *
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        // self::bootKernel();
        $this->localContainer = $this->client->getContainer();
    }

    /**
     * Test that a 404 is thrown when requesting a non-existent image
     */
    public function testPlaceholderUnavailableAction()
    {
        $this->assertTrue(true);
        // TODO: get working again
        // $this->expectException(NotFoundHttpException::class);
        $this->client->request('GET', '/non-exisitiniging.jpg/placeholder');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test that a valid response is returned upon requesting an exisiting image
     */
    public function testPlaceholderAvailableAction()
    {
        $this->assertTrue(true);
        $this->client->request('GET', PlaceholderTest::TEST_IMAGE_INPUT . "/placeholder");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(\file_exists(PlaceholderTest::TEST_IMAGE_OUTPUT . '.svg'));
        unlink(PlaceholderTest::TEST_IMAGE_OUTPUT . '.svg');
    }
}
