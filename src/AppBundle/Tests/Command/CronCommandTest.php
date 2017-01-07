<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CronCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CronCommandTest extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    protected function setUp()
    {
        self::bootKernel();
        $this->application = new Application(self::$kernel);

        $this->application->add(new CronCommand());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $command = $this->application->find('cron:SaveTargetDateTimeline');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'date' => 'あ']);
    }
}
