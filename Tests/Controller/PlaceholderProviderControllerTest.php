<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\Controller;

use BernhardWebstudio\PlaceholderBundle\DependencyInjection\BernhardWebstudioPlaceholderExtension;
use BernhardWebstudio\PlaceholderBundle\Tests\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function testPlaceholderAvailableAction()
    {
        $path = 'Tests/Fixtures/test.jpg';
        $this->client->request('GET', "$path/placeholder");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public static function getKernelClass()
    {
        return AppKernel::class;
    }
}
