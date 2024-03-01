<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Service\TextTerm\Provider\GithubIssueProvider;
use App\Service\TextTerm\Provider\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;

class TestCase extends ApiTestCase
{
    protected Client $client;
    protected Container $container;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->container = static::getContainer();

        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->migrateDb();
    }

    private function migrateDb(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove('var/test.db');

        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($metaData);
    }

    protected function mockTextTermProvider(): void
    {
        // mock ProviderInterface(GithubIssueProvider)

        $mock = $this->getMockBuilder(GithubIssueProvider::class)
            ->setConstructorArgs(['test', 'test'])
            ->onlyMethods(['fetchText'])
            ->getMock();

        $mock->method('fetchText')->willReturn('tester');

        $this->container->set(ProviderInterface::class, $mock);
    }
}
