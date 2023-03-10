<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceTestCase extends WebTestCase
{
    protected static ContainerInterface $container;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass(); // TODO: Change the autogenerated stub
    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        static::bootKernel(['environment' => 'test']);
        static::$container = static::$kernel->getContainer();
    }

    protected function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass(); // TODO: Change the autogenerated stub
    }

}
