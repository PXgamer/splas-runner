<?php

namespace pxgamer\SplasRunner;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunCommandTest extends TestCase
{
    /**
     * Test for whether an image can be downloaded
     * @throws \Exception
     */
    public function testCanDownloadImage()
    {
        $application = new Application();
        $application->add(new RunCommand());

        $command = $application->find('run');

        $commandTester = new CommandTester($command);

        $commandTester->execute(
            [
                'command' => $command->getName(),
            ]
        );
        $commandBody = $commandTester->getDisplay();

        // Check that the runner attempted to/did download an image
        $this->assertRegExp(
            '/([a-z0-9_-]+? by .*?)|Error downloading the image./i',
            $commandBody
        );
    }
}
