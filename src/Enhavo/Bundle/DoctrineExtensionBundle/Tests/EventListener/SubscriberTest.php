<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-12
 * Time: 14:02
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\EventListener;

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
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
        $finder->files()->in(__DIR__ . '/../Fixtures/Proxy');
        foreach ($finder as $file) {
            if($file->getExtension() === 'php') {
                unlink($file->getRealPath());
            }
        }

        $config = ORMSetup::createAttributeMetadataConfiguration(array($entityDir), true, __DIR__ . '/../Fixtures/Proxy');
        $conn = ['url' => 'sqlite:///:memory:'];
        $this->em = EntityManager::create($conn, $config);
    }

    protected function updateSchema()
    {
        $schema = new SchemaTool($this->em);
        $metadata = [];
        $classNames = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach($classNames as $class) {
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
        foreach($tables as $table) {
            $tableNames[] = $table['name'];
        }
        return $tableNames;
    }
}
