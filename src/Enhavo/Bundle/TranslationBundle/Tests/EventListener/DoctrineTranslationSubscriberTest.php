<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Enhavo\Bundle\TranslationBundle\EventListener\AccessControl;
use Enhavo\Bundle\TranslationBundle\EventListener\DoctrineTranslationSubscriber;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleResolver;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Component\Metadata\MetadataRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineTranslationSubscriberTest extends TestCase
{
    protected function createDependencies()
    {
        $dependencies = new DoctrineTranslationSubscriberTestDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->accessControl = $this->getMockBuilder(AccessControl::class)->disableOriginalConstructor()->getMock();
        $dependencies->metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->localeResolver = $this->getMockBuilder(LocaleResolver::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    protected function createInstance($dependencies)
    {
        $subscriber =  new DoctrineTranslationSubscriber(
            $dependencies->accessControl,
            $dependencies->metadataRepository,
            $dependencies->localeResolver,
        );

        /** @var ContainerInterface|MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('get')->willReturnCallback(function($name) use ($dependencies) {
            if ($name === TranslationManager::class) {
                return $dependencies->translationManager;
            }
            return null;
        });

        $subscriber->setContainer($container);

        return $subscriber;
    }

    public function testSubscribedEvents()
    {
        $subscriber = $this->createInstance($this->createDependencies());

        $this->assertEquals([
            'preRemove',
            'postLoad',
            'preFlush',
            'postFlush'
        ], $subscriber->getSubscribedEvents());
    }

    public function testIsAccess()
    {
        $dependencies = $this->createDependencies();
        $dependencies->accessControl->method('isAccess')->willReturn(false);
        $subscriber = $this->createInstance($dependencies);

        /** @var PreFlushEventArgs|MockObject $preFlushEventArgs */
        $preFlushEventArgs = $this->getMockBuilder(PreFlushEventArgs::class)->disableOriginalConstructor()->getMock();
        $preFlushEventArgs->expects($this->never())->method('getEntityManager');
        /** @var PostFlushEventArgs|MockObject $postFlushEventArgs */
        $postFlushEventArgs = $this->getMockBuilder(PostFlushEventArgs::class)->disableOriginalConstructor()->getMock();
        $postFlushEventArgs->expects($this->never())->method('getEntityManager');
        /** @var LifecycleEventArgs|MockObject $liveCycleEventArgs */
        $liveCycleEventArgs = $this->getMockBuilder(LifecycleEventArgs::class)->disableOriginalConstructor()->getMock();
        $liveCycleEventArgs->expects($this->never())->method('getEntity');

        $subscriber->preFlush($preFlushEventArgs);
        $subscriber->postFlush($postFlushEventArgs);
        $subscriber->postLoad($liveCycleEventArgs);
    }

    public function testPreFlush()
    {
        $dependencies = $this->createDependencies();
        $subscriber = $this->createInstance($dependencies);

        $dependencies->accessControl->method('isAccess')->willReturn(true);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->translationManager->expects($this->once())->method('detach');

        /** @var PreFlushEventArgs|MockObject $eventArgs */
        $eventArgs = $this->getMockBuilder(PreFlushEventArgs::class)->disableOriginalConstructor()->getMock();
        /** @var UnitOfWork|MockObject $unitOfWork */
        $unitOfWork = $this->getMockBuilder(UnitOfWork::class)->disableOriginalConstructor()->getMock();

        $eventArgs->method('getObjectManager')->willReturn($dependencies->entityManager);
        $dependencies->entityManager->method('getUnitOfWork')->willReturn($unitOfWork);
        $unitOfWork->method('getIdentityMap')->willReturn([
            'class1' => [new TranslatableMock()]
        ]);

        $subscriber->preFlush($eventArgs);
    }

    public function testPostFlush()
    {
        $dependencies = $this->createDependencies();
        $subscriber = $this->createInstance($dependencies);

        $dependencies->accessControl->method('isAccess')->willReturn(true);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->translationManager->expects($this->once())->method('translate');

        /** @var PostFlushEventArgs|MockObject $eventArgs */
        $eventArgs = $this->getMockBuilder(PostFlushEventArgs::class)->disableOriginalConstructor()->getMock();
        /** @var UnitOfWork|MockObject $unitOfWork */
        $unitOfWork = $this->getMockBuilder(UnitOfWork::class)->disableOriginalConstructor()->getMock();

        $eventArgs->method('getObjectManager')->willReturn($dependencies->entityManager);
        $dependencies->entityManager->method('getUnitOfWork')->willReturn($unitOfWork);
        $unitOfWork->method('getIdentityMap')->willReturn([
            'class1' => [new TranslatableMock()]
        ]);

        $subscriber->postFlush($eventArgs);
    }

    public function testPostLoad()
    {
        $dependencies = $this->createDependencies();
        $subscriber = $this->createInstance($dependencies);

        $dependencies->accessControl->method('isAccess')->willReturn(true);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);

        $dependencies->translationManager->expects($this->once())->method('translate');

        /** @var LifecycleEventArgs|MockObject $eventArgs */
        $eventArgs = $this->getMockBuilder(LifecycleEventArgs::class)->disableOriginalConstructor()->getMock();

        $eventArgs->method('getEntity')->willReturn(new TranslatableMock());

        $subscriber->postLoad($eventArgs);
    }

    public function testPreRemove()
    {
        $dependencies = $this->createDependencies();
        $subscriber = $this->createInstance($dependencies);

        $dependencies->accessControl->method('isAccess')->willReturn(true);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);

        $dependencies->translationManager->expects($this->once())->method('delete');

        /** @var LifecycleEventArgs|MockObject $eventArgs */
        $eventArgs = $this->getMockBuilder(LifecycleEventArgs::class)->disableOriginalConstructor()->getMock();

        $eventArgs->method('getEntity')->willReturn(new TranslatableMock());

        $subscriber->preRemove($eventArgs);
    }
}

class DoctrineTranslationSubscriberTestDependencies
{
    /** @var TranslationManager|MockObject */
    public $translationManager;

    /** @var AccessControl|MockObject */
    public $accessControl;

    /** @var MetadataRepository|MockObject */
    public $metadataRepository;

    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var LocaleResolver|MockObject */
    public $localeResolver;
}
