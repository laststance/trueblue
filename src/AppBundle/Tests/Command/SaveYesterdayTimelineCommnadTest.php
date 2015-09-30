<?php

namespace AppBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use AppBundle\Command\SaveYesterdayTimelineCommnad;

class SaveYesterdayTimelineCommnadTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        // mock the Kernel or create one depending on your needs
        $kernel = $this->getMock('Symfony\Component\HttpKernel\Kernel', array(), array(), '', false, false);
        $application = new Application($kernel);
        $application->add(new SaveYesterdayTimelineCommnad());

        $command = $application->find('cron:SaveYesterdayTimeline');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertTrue(true);

    }
}
