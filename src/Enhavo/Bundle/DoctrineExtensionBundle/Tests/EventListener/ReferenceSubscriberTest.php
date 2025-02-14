<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-09
 * Time: 14:20
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\EventListener;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\ClassNameResolver;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EventListener\ReferenceSubscriber;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\Entity;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\EntityContainer;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\NodeContainer;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\NodeOne;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\MockObject\MockObject;

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
                        'idField' => 'nodeId',
                        'cascade' => ['persist']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $node = new NodeOne();
        $node->setName('one');

        $entity = new Entity();
        $entity->setName('node_one');
        $entity->setNode($node);

        $entityContainer = new EntityContainer();
        $entityContainer->setName('container_one');
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

    public function testReferenceSameObject()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $node = new NodeOne();
        $node->setName('same');

        $entityOne = new Entity();
        $entityOne->setName('entity_one');
        $entityOne->setNode($node);

        $entityTwo = new Entity();
        $entityTwo->setName('entity_two');
        $entityTwo->setNode($node);

        $this->em->persist($entityOne);
        $this->em->persist($entityTwo);
        $this->em->flush();
        $this->em->clear();

        $this->assertCount(1, $this->em->getRepository(NodeOne::class)->findAll());
    }

    public function testPersistence()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $node = new NodeOne();
        $node->setName('inner');

        $entityInner = new Entity();
        $entityInner->setName('entity_inner');
        $entityInner->setNode($node);

        $nodeContainer = new NodeContainer();
        $nodeContainer->setName('node_container');
        $nodeContainer->setEntity($entityInner);

        $entityOuter = new Entity();
        $entityOuter->setName('entity_outer');
        $entityOuter->setNode($nodeContainer);

        $this->em->persist($entityOuter);
        $this->em->flush();
        $this->em->clear();

        $entityOuter = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity_outer']);

        $this->assertEquals('node_container', $entityOuter->getNode()->getName());
        $this->assertEquals('entity_inner', $entityOuter->getNode()->getEntity()->getName());
        $this->assertEquals('inner', $entityOuter->getNode()->getEntity()->getNode()->getName());
    }

    public function testDeleteEntityThatIsReferenced()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        // Entity has a reference to Node, so we create an Entity class with a Node and try to delete only the Node

        $node = new NodeOne();
        $node->setName('node');

        $entity = new Entity();
        $entity->setName('entity');
        $entity->setNode($node);

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $this->em->remove($entity->getNode());
        $this->em->flush();
        $this->em->clear();

        $node = $this->em->getRepository(NodeOne::class)->findOneBy(['name' => 'node']);

        $this->assertNull($node);
    }

    public function testCascadeDeleteEntity()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        // Entity has a reference to Node, so we create an Entity class with a Node and try to delete only the Node

        $node = new NodeOne();
        $node->setName('node');

        $entity = new Entity();
        $entity->setName('entity');
        $entity->setNode($node);

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $this->em->remove($entity);
        $this->em->flush();
        $this->em->clear();

        $node = $this->em->getRepository(NodeOne::class)->findOneBy(['name' => 'node']);

        $this->assertNull($node);
    }

    public function testCascadeDeleteContainingNullEntity()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entity = new Entity();
        $entity->setName('entity');
        $entity->setNode(null);

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $this->em->remove($entity);
        $this->em->flush();
        $this->em->clear();

        $this->expectNotToPerformAssertions();
    }

    public function testPersistOnFetchEntity()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);


        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entity = new Entity();
        $entity->setName('entity');
        $entity->setNode(null);

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $node = new NodeOne();
        $node->setName('node');
        $entity->setNode($node);

        $this->em->flush();
        $this->em->clear();

        $node = $this->em->getRepository(NodeOne::class)->findOneBy(['name' => 'node']);

        $this->assertNotNull($node);
    }

    public function testCascadePersistAndRemoveOnFetchEntity()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);


        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entityOne = new Entity();
        $entityOne->setName('one');
        $entityOne->setNode(null);

        $this->em->persist($entityOne);
        $this->em->flush();
        $this->em->clear();

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);

        $nodeContainerOne = new NodeContainer();
        $nodeContainerOne->setName('one');
        $entityOne->setNode($nodeContainerOne);

        $entityTwo = new Entity();
        $entityTwo->setName('two');
        $nodeContainerOne->setEntity($entityTwo);

        $nodeContainerTwo = new NodeContainer();
        $nodeContainerTwo->setName('two');
        $entityTwo->setNode($nodeContainerTwo);

        $entityThree = new Entity();
        $entityThree->setName('three');
        $nodeContainerTwo->setEntity($entityThree);

        $nodeOne = new NodeOne();
        $nodeOne->setName('one');
        $entityThree->setNode($nodeOne);

        $this->em->flush();
        $this->em->clear();

        $this->assertNotNull($this->em->getRepository(NodeContainer::class)->findOneBy(['name' => 'one']));
        $this->assertNotNull($this->em->getRepository(NodeContainer::class)->findOneBy(['name' => 'two']));
        $this->assertNotNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'two']));
        $this->assertNotNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'three']));
        $this->assertNotNull($this->em->getRepository(NodeOne::class)->findOneBy(['name' => 'one']));

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);
        $this->em->remove($entityOne);

        $this->em->flush();
        $this->em->clear();

        $this->assertNull($this->em->getRepository(NodeContainer::class)->findOneBy(['name' => 'one']));
        //$this->assertNull($this->em->getRepository(NodeContainer::class)->findOneBy(['name' => 'two']));
        //$this->assertNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'two']));
        //$this->assertNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'three']));
        //$this->assertNull($this->em->getRepository(NodeOne::class)->findOneBy(['name' => 'one']));
    }

    public function testReferenceSameEntity()
    {
        $this->bootstrap(__DIR__ . "/../Fixtures/Entity/Reference");

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove']
                    ]
                ]
            ]
        ]);

        $subscriber = $this->createInstance($dependencies);

        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $nodeOne = new NodeOne();
        $nodeOne->setName('one');

        $entityOne = new Entity();
        $entityOne->setName('one');
        $entityOne->setNode($nodeOne);

        $entityTwo = new Entity();
        $entityTwo->setName('two');
        $entityTwo->setNode($nodeOne);

        $this->em->persist($entityOne);
        $this->em->persist($entityTwo);
        $this->em->flush();
        $this->em->clear();

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);
        $entityTwo = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'two']);

        $this->assertNotNull($entityOne);
        $this->assertNotNull($entityTwo);
        $this->assertNotNull($entityTwo->getNode());
        $this->assertTrue($entityTwo->getNode() === $entityOne->getNode());
    }
}

class ReferenceSubscriberDependencies
{
    public MetadataRepository|MockObject $metadataRepository;
    public EntityResolverInterface|MockObject $entityResolver;
}
