<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Metadata;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Provider\ExtendProvider;
use Enhavo\Bundle\DoctrineExtensionBundle\Metadata\Provider\ReferenceProvider;
use Enhavo\Component\Metadata\Driver\ConfigurationDriver;
use Enhavo\Component\Metadata\MetadataFactory;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

abstract class SubscriberTest extends TestCase
{
    /** @var EntityManager */
    protected $em;

    protected function bootstrap($entityDir)
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../Fixtures/Proxy');
        foreach ($finder as $file) {
            if ('php' === $file->getExtension()) {
                unlink($file->getRealPath());
            }
        }

        $config = ORMSetup::createAttributeMetadataConfiguration([$entityDir], true, __DIR__.'/../Fixtures/Proxy');
        $conn = ['url' => 'sqlite:///:memory:'];
        $this->em = EntityManager::create($conn, $config);
    }

    protected function updateSchema()
    {
        $schema = new SchemaTool($this->em);
        $metadata = [];
        $classNames = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($classNames as $class) {
            $metadata[] = $this->em->getClassMetadata($class);
        }
        $schema->createSchema($metadata);
    }

    protected function createMetadataRepository($configuration)
    {
        $factory = new MetadataFactory(Metadata::class);
        $factory->addDriver(new ConfigurationDriver($configuration));
        $factory->addProvider(new ExtendProvider());
        $factory->addProvider(new ReferenceProvider());

        return new MetadataRepository($factory, false);
    }

    protected function getTableNames()
    {
        $tables = $this->em->getConnection()->query('SELECT name FROM sqlite_master WHERE type =\'table\' AND name NOT LIKE \'sqlite_%\';')->fetchAll();
        $tableNames = [];
        foreach ($tables as $table) {
            $tableNames[] = $table['name'];
        }

        return $tableNames;
    }
}
