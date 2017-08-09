<?php

use PHPUnit\Framework\TestCase;
use pxgamer\SplasRunner\RunCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunCommandTest extends TestCase
{
    /**
     * Test for whether an image can be downloaded
     */
    public function testCanDownloadImage()
    {
        $application = new Application();
        $application->add(new RunCommand());

        $command = $application->find('run');

        $commandTester = new CommandTester($command);

        $commandTester->execute(
            [
                'command' => $command->getName()
            ]
        );
        $commandBody = $commandTester->getDisplay();

        // Check that the runner attempted to/did download an image
        $this->assertRegExp(
            '/Grabbing image: |Error downloading the image./',
            $commandBody
        );
    }
}