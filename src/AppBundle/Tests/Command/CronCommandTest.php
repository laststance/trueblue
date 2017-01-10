<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CronCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CronCommandTest extends MyKernelTestCase
{
    protected static $fixtures = [__DIR__.'/../DataFixtures/Alice/user.yml'];

    /**
     * @var Application
     */
    private $application;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var Command
     */
    private $command;

    protected function setUp()
    {
        self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->application->add(new CronCommand());
        $this->command = $this->application->find('cron:SaveTargetDateTimeline');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgument()
    {
        $this->commandTester->execute(['command' => $this->command->getName(), 'date' => 'あ']);
    }

    public function testShouldBePersist()
    {
        // TODO: TwitterAPIのモックを作成
        // TODO: Userのモックを作成

        // TODO: persistされたデータをDBからSelectし、正しいデータが入っているか確認する
    }
}
