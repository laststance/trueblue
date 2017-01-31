<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\CronCommand;
use AppBundle\Entity\PastTimeline;
use AppBundle\Entity\User;
use AppBundle\Service\TwitterAPIService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Phake;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    private $mockApiResponse = ['timeline_json' => ['id' => 10, 'body' => 'foo']];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        self::bootKernel();
        $this->application = new Application(self::$kernel);
        $this->application->add(new CronCommand());
        $this->command = $this->application->find('cron:SaveTargetDateTimeline');
        $this->commandTester = new CommandTester($this->command);
        $this->container = self::$kernel->getCOntainer();
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
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
        $this->setMocks();

        $exitStatus = $this->commandTester->execute(['command' => $this->command->getName(), 'date' => '2020-12-12']);
        $this->assertEquals(0, $exitStatus);

        $user = $this->getFixtureUser();
        $pastTimeLine = $this->getAssertTarget($user);

        $this->assertEquals($pastTimeLine->getUser()->getId(), $user->getId());
        $this->assertEquals(
            json_decode($pastTimeLine->getTimelineJson(), true),
            $this->mockApiResponse['timeline_json']
        );

        $this->cleanDB($pastTimeLine);
    }

    private function setMocks()
    {
        $mockApi = Phake::mock(TwitterAPIService::class);
        Phake::when($mockApi)->findIdRangeByDate(Phake::anyParameters())->thenReturn($this->mockApiResponse);
        $this->container->set('twitter_api', $mockApi);

        $mockRepository = Phake::mock(EntityRepository::class);
        $fixtureUserArray = $this->getFixtureUserArray();
        Phake::when($mockRepository)->findAll()->thenReturn($fixtureUserArray);

        $mockDoctrine = Phake::mock(Registry::class);
        Phake::when($mockDoctrine)->getRepository('AppBundle:User')->thenReturn($mockRepository);
        $this->container->set('doctrine', $mockDoctrine);
    }

    private function getFixtureUserArray()
    {
        return $this->entityManager->getRepository(
            'AppBundle:User'
        )->findBy(['username' => 'malloc007']);
    }

    private function getFixtureUser()
    {
        return $this->entityManager->getRepository(
            'AppBundle:User'
        )->findOneBy(['username' => 'malloc007']);
    }

    private function getAssertTarget(User $user)
    {
        return $this->entityManager->getRepository(
            'AppBundle:PastTimeline'
        )->findOneBy(
            ['user' => $user]
        );
    }

    private function cleanDB(PastTimeline $pastTimeLine)
    {
        $this->entityManager->remove($pastTimeLine);
        $this->entityManager->flush();
    }
}
