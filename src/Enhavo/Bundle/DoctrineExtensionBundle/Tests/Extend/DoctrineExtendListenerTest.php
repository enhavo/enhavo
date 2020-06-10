<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 14:20
 */

namespace Enhavo\Component\DoctrineExtension\Tests\Extend;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Enhavo\Component\DoctrineExtension\Extend\DoctrineExtendSubscriber;
use Enhavo\Component\DoctrineExtension\Extend\ExtendMappingExtension;
use Enhavo\Component\DoctrineExtension\Mapping\MappingExtensionDriver;
use Enhavo\Component\DoctrineExtension\Tests\Extend\Entity\Root;
use PHPUnit\Framework\TestCase;

class DoctrineExtendListenerTest extends TestCase
{
    public function testListener()
    {
        $config = Setup::createXMLMetadataConfiguration(array(__DIR__ . "/../Fixtures/extend/config"), true);

        $conn = array(
            'url' => 'sqlite:///:memory:',
        );

        $entityManager = EntityManager::create($conn, $config);

        $driver = new MappingExtensionDriver($entityManager->getConfiguration()->getMetadataDriverImpl());
        $driver->addExtension(new ExtendMappingExtension());
        $entityManager->getConfiguration()->setMetadataDriverImpl($driver);

        $eventSubscriber = new DoctrineExtendSubscriber();
        $entityManager->getEventManager()->addEventSubscriber($eventSubscriber);

        $root = $entityManager->getClassMetadata(Root::class);

        $schema = new SchemaTool($entityManager);
        $schema->createSchema([$root]);

        $tables = $entityManager->getConnection()->query('SELECT name FROM sqlite_master WHERE type =\'table\' AND name NOT LIKE \'sqlite_%\';')->fetchAll();
    }
}
