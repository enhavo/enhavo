<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 14:20
 */

namespace Enhavo\Component\DoctrineExtension\Tests\Extend;

use Enhavo\Bundle\DoctrineExtensionBundle\EventListener\ExtendSubscriber;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\EventListener\SubscriberTest;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Extend\Child;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Extend\Entity;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Extend\Root;
use Enhavo\Component\Metadata\MetadataRepository;

class ExtendSubscriberTest extends SubscriberTest
{
    private function createDependencies($configuration)
    {
        $dependencies = new ExtendSubscriberDependencies();
        $dependencies->metadataRepository = $this->createMetadataRepository($configuration);
        return $dependencies;
    }

    private function createInstance(ExtendSubscriberDependencies $dependencies)
    {
        return new ExtendSubscriber($dependencies->metadataRepository);
    }

    public function testSubscriber()
    {
        $dependencies = $this->createDependencies([
            Entity::class => [
                'extends' => Root::class,
                'discrName' => 'entity'
            ],
            Child::class => [
                'extends' => Entity::class,
                'discrName' => 'child'
            ],
        ]);
        $subscriber = $this->createInstance($dependencies);

        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Extend");
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $tableNames = $this->getTableNames();

        $this->assertTrue(in_array('Root', $tableNames));
        $this->assertFalse(in_array('Entity', $tableNames));
        $this->assertFalse(in_array( 'Child', $tableNames));

        $root = new Root();
        $entity = new Entity();
        $child = new Child();

        $this->em->persist($root);
        $this->em->persist($entity);
        $this->em->persist($child);

        $this->em->flush();

        $entries = $this->em->getConnection()->fetchAll('SELECT * FROM Root ORDER BY discr ASC');

        $this->assertEquals('child', $entries[0]['discr']);
        $this->assertEquals('entity', $entries[1]['discr']);
        $this->assertEquals('root', $entries[2]['discr']);
    }
}

class ExtendSubscriberDependencies
{
    /** @var MetadataRepository|\PHPUnit_Framework_MockObject_MockObject */
    public $metadataRepository;
}
