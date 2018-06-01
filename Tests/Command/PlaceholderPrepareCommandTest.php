<?php

namespace BernhardWebstudio\PlaceholderBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use BernhardWebstudio\PlaceholderBundle\Tests\AppKernel;
use BernhardWebstudio\PlaceholderBundle\Commands\PlaceholderPrepareCommand;

class PlaceholderPrepareCommandTest extends KernelTestCase
{
    protected $application;

    public function setUp() {
        $kernel = self::bootKernel();
        $this->application = new Application($kernel);
        $localContainer = $kernel->getContainer();
        $provider = $localContainer->get("bewe_placeholder.provider");
        $this->application->add(new PlaceholderPrepareCommand($provider));
    }

    public function testExecute()
    {
        $command = $this->application->find('bewe:placeholder:prepare');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            // pass options to the helper
            '--dry' => 'true',
        ));

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Dry run', $output);
    }

    public static function getKernelClass()
    {
        return AppKernel::class;
    }
}
