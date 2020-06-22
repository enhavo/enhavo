<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 14:20
 */

namespace Enhavo\Component\DoctrineExtension\Tests\Extend;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\ClassNameResolver;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EventListener\ReferenceSubscriber;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\EventListener\SubscriberTest;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\Entity;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\EntityContainer;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\NodeOne;
use Enhavo\Component\Metadata\MetadataRepository;

class ReferenceSubscriberTest extends SubscriberTest
{
    private function createDependencies($configuration)
    {
        $dependencies = new ReferenceSubscriberDependencies();
        $dependencies->metadataRepository = $this->createMetadataRepository($configuration);
        $dependencies->entityResolver = new ClassNameResolver($this->em);
        return $dependencies;
    }

    private function createInstance(ReferenceSubscriberDependencies $dependencies)
    {
        return new ReferenceSubscriber($dependencies->metadataRepository, $dependencies->entityResolver);
    }

    public function testSubscriber()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId'
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entityContainer = new EntityContainer();
        $entityContainer->setName('container_one');
        $node = new NodeOne();
        $node->setName('one');
        $entity = new Entity();
        $entity->setName('node_one');
        $entity->setNode($node);
        $entityContainer->setEntity($entity);
        $this->em->persist($entityContainer);
        $this->em->flush();
        $this->em->clear();

        unset($node);
        unset($entity);

        /** @var Entity $entity */
        $entityContainer = $this->em->getRepository(EntityContainer::class)->findOneBy([
            'name' => 'container_one'
        ]);
        $this->assertEquals('one', $entityContainer->getEntity()->getNode()->getName());

        $this->em->clear();
        unset($entityContainer);

        /** @var Entity $entity */
        $entity = $this->em->getRepository(Entity::class)->findOneBy([
            'name' => 'node_one'
        ]);

        $this->assertEquals('one', $entity->getNode()->getName());
        $entity->setNode(null);
        $this->em->flush();

        $this->assertNull($entity->getNodeId());
        $this->assertNull($entity->getNodeName());

        $this->em->clear();
        unset($entity);
    }
}

class ReferenceSubscriberDependencies
{
    /** @var MetadataRepository|\PHPUnit_Framework_MockObject_MockObject */
    public $metadataRepository;
    /** @var EntityResolverInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $entityResolver;
}
