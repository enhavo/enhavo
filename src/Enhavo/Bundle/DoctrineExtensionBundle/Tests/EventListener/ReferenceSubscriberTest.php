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

use Doctrine\Persistence\Proxy;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\ClassNameResolver;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EventListener\ReferenceSubscriber;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\Entity;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\EntityContainer;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\NodeBase;
use Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\Reference\NodeEntity;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\MockObject\MockObject;

class ReferenceSubscriberTest extends SubscriberTest
{
    private function createDependencies(array $configuration)
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
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $node = new NodeBase();
        $node->name = 'one';

        $entity = new Entity();
        $entity->name = 'node_one';
        $entity->node = $node;

        $entityContainer = new EntityContainer();
        $entityContainer->name = 'container_one';
        $entityContainer->entity = $entity;

        $this->em->persist($entityContainer);
        $this->em->flush();
        $this->em->clear();

        unset($node);
        unset($entity);

        /** @var Entity $entity */
        $entityContainer = $this->em->getRepository(EntityContainer::class)->findOneBy([
            'name' => 'container_one',
        ]);

        // Should not be needed
        if ($entityContainer->entity instanceof Proxy) {
            $entityContainer->entity->__load();
        }

        $this->assertEquals('one', $entityContainer->entity->node->name);

        $this->em->clear();
        unset($entityContainer);

        /** @var Entity $entity */
        $entity = $this->em->getRepository(Entity::class)->findOneBy([
            'name' => 'node_one',
        ]);

        $this->assertEquals('one', $entity->node->name);
        $entity->node = null;
        $this->em->flush();

        $this->assertNull($entity->nodeId);
        $this->assertNull($entity->nodeName);

        $this->em->clear();
        unset($entity);
    }

    public function testReferenceSameObject()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $node = new NodeBase();
        $node->name = 'same';

        $entityOne = new Entity();
        $entityOne->name = 'entity_one';
        $entityOne->node = $node;

        $entityTwo = new Entity();
        $entityTwo->name = 'entity_two';
        $entityTwo->node = $node;

        $this->em->persist($entityOne);
        $this->em->persist($entityTwo);
        $this->em->flush();
        $this->em->clear();

        $this->assertCount(1, $this->em->getRepository(NodeBase::class)->findAll());
    }

    public function testPersistence()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $node = new NodeBase();
        $node->name = 'inner';

        $entityInner = new Entity();
        $entityInner->name = 'entity_inner';
        $entityInner->node = $node;

        $nodeTwo = new NodeEntity();
        $nodeTwo->name = 'node_container';
        $nodeTwo->entity = $entityInner;

        $entityOuter = new Entity();
        $entityOuter->name = 'entity_outer';
        $entityOuter->node = $nodeTwo;

        $this->em->persist($entityOuter);
        $this->em->flush();
        $this->em->clear();

        $entityOuter = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity_outer']);

        $this->assertEquals('node_container', $entityOuter->node->name);
        $this->assertEquals('entity_inner', $entityOuter->node->entity->name);
        $this->assertEquals('inner', $entityOuter->node->entity->node->name);
    }

    public function testDeleteEntityThatIsReferenced()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        // Entity has a reference to Node, so we create an Entity class with a Node and try to delete only the Node

        $node = new NodeBase();
        $node->name = 'node';

        $entity = new Entity();
        $entity->name = 'entity';
        $entity->node = $node;

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $this->em->remove($entity->node);
        $this->em->flush();
        $this->em->clear();

        $node = $this->em->getRepository(NodeBase::class)->findOneBy(['name' => 'node']);

        $this->assertNull($node);
    }

    public function testCascadeDeleteEntity()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        // Entity has a reference to Node, so we create an Entity class with a Node and try to delete only the Node

        $node = new NodeBase();
        $node->name = 'node';

        $entity = new Entity();
        $entity->name = 'entity';
        $entity->node = $node;

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $this->em->remove($entity);
        $this->em->flush();
        $this->em->clear();

        $node = $this->em->getRepository(NodeBase::class)->findOneBy(['name' => 'node']);

        $this->assertNull($node);
    }

    public function testCascadeDeleteContainingNullEntity()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);
        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entity = new Entity();
        $entity->name = 'entity';
        $entity->node = null;

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
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);

        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entity = new Entity();
        $entity->name = 'entity';
        $entity->node = null;

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();

        $entity = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'entity']);

        $node = new NodeBase();
        $node->name = 'node';
        $entity->node = $node;

        $this->em->flush();
        $this->em->clear();

        $node = $this->em->getRepository(NodeBase::class)->findOneBy(['name' => 'node']);

        $this->assertNotNull($node);
    }

    public function testCascadePersistAndRemoveOnFetchEntity()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);

        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $entityOne = new Entity();
        $entityOne->name = 'one';
        $entityOne->node = null;

        $this->em->persist($entityOne);
        $this->em->flush();
        $this->em->clear();

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);

        $nodeOne = new NodeEntity();
        $nodeOne->name = 'one';
        $entityOne->node = $nodeOne;

        $entityTwo = new Entity();
        $entityTwo->name = 'two';
        $nodeOne->entity = $entityTwo;

        $nodeTwo = new NodeEntity();
        $nodeTwo->name = 'two';
        $entityTwo->node = $nodeTwo;

        $entityThree = new Entity();
        $entityThree->name = 'three';
        $nodeTwo->entity = $entityThree;

        $nodeOne = new NodeBase();
        $nodeOne->name = 'one';
        $entityThree->node = $nodeOne;

        $this->em->flush();
        $this->em->clear();

        $this->assertNotNull($this->em->getRepository(NodeEntity::class)->findOneBy(['name' => 'one']));
        $this->assertNotNull($this->em->getRepository(NodeEntity::class)->findOneBy(['name' => 'two']));
        $this->assertNotNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'two']));
        $this->assertNotNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'three']));
        $this->assertNotNull($this->em->getRepository(NodeBase::class)->findOneBy(['name' => 'one']));

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);
        $this->em->remove($entityOne);

        $this->em->flush();
        $this->em->clear();

        $this->assertNull($this->em->getRepository(NodeEntity::class)->findOneBy(['name' => 'one']));
        $this->assertNull($this->em->getRepository(NodeEntity::class)->findOneBy(['name' => 'two']));
        $this->assertNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'two']));
        $this->assertNull($this->em->getRepository(Entity::class)->findOneBy(['name' => 'three']));
        $this->assertNull($this->em->getRepository(NodeBase::class)->findOneBy(['name' => 'one']));
    }

    public function testReferenceSameEntity()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);

        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $nodeOne = new NodeBase();
        $nodeOne->name = 'one';

        $entityOne = new Entity();
        $entityOne->name = 'one';
        $entityOne->node = $nodeOne;

        $entityTwo = new Entity();
        $entityTwo->name = 'two';
        $entityTwo->node = $nodeOne;

        $this->em->persist($entityOne);
        $this->em->persist($entityTwo);
        $this->em->flush();
        $this->em->clear();

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);
        $entityTwo = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'two']);

        $this->assertNotNull($entityOne);
        $this->assertNotNull($entityTwo);
        $this->assertNotNull($entityTwo->node);
        $this->assertTrue($entityTwo->node === $entityOne->node);
    }

    public function testPersistWithCascadeLoop()
    {
        $this->bootstrap(__DIR__.'/../Fixtures/Entity/Reference');

        $dependencies = $this->createDependencies([
            Entity::class => [
                'reference' => [
                    'node' => [
                        'nameField' => 'nodeName',
                        'idField' => 'nodeId',
                        'cascade' => ['persist', 'remove'],
                    ],
                ],
            ],
        ]);

        $subscriber = $this->createInstance($dependencies);

        $this->em->getEventManager()->addEventSubscriber($subscriber);
        $this->updateSchema();

        $nodeOne = new NodeEntity();
        $nodeOne->name = 'one';

        $entityOne = new Entity();
        $entityOne->name = 'one';
        $entityOne->node = $nodeOne;
        $nodeOne->entity = $entityOne;

        $this->em->persist($entityOne);
        $this->em->flush();
        $this->em->clear();

        $entityOne = $this->em->getRepository(Entity::class)->findOneBy(['name' => 'one']);

        $this->assertNotNull($entityOne);
        $this->assertNotNull($entityOne->node);
        $this->assertNotNull($entityOne->node->entity);
    }
}

class ReferenceSubscriberDependencies
{
    public MetadataRepository|MockObject $metadataRepository;
    public EntityResolverInterface|MockObject $entityResolver;
}
