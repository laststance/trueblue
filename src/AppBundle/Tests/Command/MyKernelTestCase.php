<?php

namespace AppBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MyKernelTestCase extends KernelTestCase
{
    public static function setUpBeforeClass()
    {
        self::bootKernel();
        $manager = self::$kernel->getContainer()->get('h4cc_alice_fixtures.manager');
        static::$fixtures = $manager->loadFiles(static::$fixtures);
        $manager->persist(static::$fixtures);
    }

    public static function tearDownAfterClass()
    {
        self::bootKernel();
        $connection = self::$kernel->getContainer()->get('doctrine.orm.default_entity_manager')->getConnection();
        $connection->exec('SET FOREIGN_KEY_CHECKS = 0');
        $manager = self::$kernel->getContainer()->get('h4cc_alice_fixtures.manager');
        $manager->remove(static::$fixtures);
    }
}
