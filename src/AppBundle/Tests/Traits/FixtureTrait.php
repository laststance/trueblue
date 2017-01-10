<?php

namespace AppBundle\Tests\Controller\Traits;

trait FixtureTrait
{
    public static function setUpBeforeClass()
    {
        $client = static::createClient();
        $manager = $client->getContainer()->get('h4cc_alice_fixtures.manager');
        static::$fixtures = $manager->loadFiles(static::$fixtures);
        $manager->persist(static::$fixtures);
    }

    public static function tearDownAfterClass()
    {
        $client = static::createClient();
        $connection = $client->getContainer()->get('doctrine.orm.default_entity_manager')->getConnection();
        $manager = $client->getContainer()->get('h4cc_alice_fixtures.manager');
        $manager->remove(static::$fixtures);
    }
}
