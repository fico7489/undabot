<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

class TestCase extends KernelTestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->container = static::getContainer();
    }
}
