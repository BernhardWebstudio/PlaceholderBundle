<?php 

namespace BernhardWebstudio\PlaceholderBundle\Tests;

use PHPUnit\Framework\TestCase;
use BernhardWebstudio\PlaceholderBundle\BernhardWebstudioPlaceholderBundle;

class PlaceholderTest extends TestCase {
    const TEST_IMAGE_INPUT = 'Tests/Fixtures/test.jpg';

    const TEST_IMAGE_OUTPUT = 'Tests/Fixtures/test_thumb.jpg';

    public function testAutoloader() {
        $test = new BernhardWebstudioPlaceholderBundle();

        $this->assertInstanceOf(BernhardWebstudioPlaceholderBundle::class, $test);
    }
}