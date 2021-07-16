<?php


namespace Enhavo\Bundle\NewsletterBundle\Tests\Strategy;


use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\NewsletterBundle\Entity\PendingSubscriber;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Pending\PendingSubscriberManager;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\DoubleOptInStrategyType;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\StrategyType;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class StrategyDoubleOptInTypeTest extends TestCase
{
    private function createDependencies(): StrategyDoubleOptInTypeTestDependencies
    {
        $dependencies = new StrategyDoubleOptInTypeTestDependencies();
        $dependencies->pendingManager = $this->getMockBuilder(PendingSubscriberManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->newsletterManager = $this->getMockBuilder(NewsletterManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            return $message.'.trans';
        });
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->storage = $this->getMockBuilder(Storage::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    private function createInstance(TypeInterface $type, array $parents, array $options): Strategy
    {
        $strategy = new Strategy($type, $parents, $options);
        $type->setParent($parents[0]);
        return $strategy;
    }

    public function testAddSubscriber()
    {
        $dependencies = $this->createDependencies();
        $dependencies->pendingManager->expects($this->once())->method('createFrom');
        $dependencies->pendingManager->expects($this->once())->method('save')->willReturnCallback(function (PendingSubscriber $subscriber) {

        });
        $dependencies->eventDispatcher->expects($this->exactly(2))->method('dispatch')->willReturnCallback(function ($key, $event) {
            $this->assertInstanceOf(SubscriberEvent::class, $event);
            $this->assertInstanceOf(SubscriberInterface::class, $event->getSubscriber());
        });
        $dependencies->router->expects($this->once())->method('generate')->willReturnCallback(function ($name) {
            $this->assertEquals('__ROUTE_NAME__', $name);

            return $name.'.routed';
        });
        $dependencies->newsletterManager->expects($this->once())->method('createMessage')->willReturnCallback(function ($from, $senderName, $to, $subject, $template, $options) {

            $this->assertEquals('from@enhavo.com', $from);
            $this->assertEquals('enhavo', $senderName);
            $this->assertEquals('to@enhavo.com', $to);
            $this->assertEquals('subscriber.mail.notify.subject.trans', $subject);
            $this->assertEquals('__NTPL__', $template);

            $this->assertInstanceOf(SubscriberInterface::class, $options['subscriber']);
            $this->assertEquals('__ROUTE_NAME__.routed', $options['link']);

            return new Message();
        });

        /** @var SubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $subscriber->expects($this->once())->method('setCreatedAt')->willReturnCallback(function (\DateTime $date) {

        });
        $subscriber->expects($this->once())->method('setConfirmationToken');
        $subscriber->expects($this->once())->method('getSubscription')->willReturn('default');
        $subscriber->expects($this->once())->method('getConfirmationToken')->willReturn('__TOKEN__');

        $strategyType = new DoubleOptInStrategyType($dependencies->newsletterManager, $dependencies->pendingManager, $dependencies->router);
        $strategyType->setTranslator($dependencies->translator);

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'activate_route' => '__ROUTE_NAME__',
            'activate_route_parameters' => [],
            'admin_template' => '__ATPL__',
            'template' => '__NTPL__',
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
            'translation_domain' => ''

        ]);
        $this->assertEquals('subscriber.form.message.double_opt_in', $strategy->addSubscriber($subscriber));
    }

    public function testActivateSubscriber()
    {
        $dependencies = $this->createDependencies();
        $dependencies->pendingManager->expects($this->once())->method('removeBy')->willReturnCallback(function ($email, $subscription) {
            $this->assertEquals('to@enhavo.com', $email);
            $this->assertEquals('default', $subscription);
        });
        $dependencies->eventDispatcher->expects($this->exactly(2))->method('dispatch')->willReturnCallback(function ($key, $event) {
            $this->assertInstanceOf(SubscriberEvent::class, $event);
            $this->assertInstanceOf(SubscriberInterface::class, $event->getSubscriber());

        });
        $dependencies->storage->expects($this->once())->method('saveSubscriber');
        $dependencies->newsletterManager->expects($this->once())->method('createMessage')->willReturnCallback(function ($from, $senderName, $to, $subject, $template, $options) {
            $this->assertEquals('from@enhavo.com', $from);
            $this->assertEquals('enhavo', $senderName);
            $this->assertEquals('to@enhavo.com', $to);
            $this->assertEquals('subscriber.mail.confirm.subject.trans', $subject);
            $this->assertEquals('__CTPL__', $template);
            $this->assertInstanceOf(SubscriberInterface::class, $options['subscriber']);

            return new Message();
        });

        /** @var SubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriber->expects($this->exactly(2))->method('getEmail')->willReturn('to@enhavo.com');
        $subscriber->expects($this->once())->method('getSubscription')->willReturn('default');

        $strategyType = new DoubleOptInStrategyType($dependencies->newsletterManager, $dependencies->pendingManager, $dependencies->router);
        $strategyType->setTranslator($dependencies->translator);

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'activate_route' => '__ROUTE_NAME__',
            'activate_route_parameters' => [],
            'admin_template' => '__ATPL__',
            'template' => '__TPL__',
            'confirmation_template' => '__CTPL__',
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
            'admin_subject' => '__SUBJECT__',
            'translation_domain' => ''

        ]);
        $strategy->setStorage($dependencies->storage);
        $strategy->activateSubscriber($subscriber);
    }

    public function testActivateSubscriber2()
    {
        $dependencies = $this->createDependencies();
        $dependencies->pendingManager->expects($this->once())->method('removeBy')->willReturnCallback(function ($email, $subscription) {
            $this->assertEquals('to@enhavo.com', $email);
            $this->assertEquals('default', $subscription);
        });
        $dependencies->storage->expects($this->once())->method('saveSubscriber');
        $dependencies->newsletterManager->expects($this->once())->method('createMessage')->willReturnCallback(function ($from, $senderName, $to, $subject, $template, $options) {
            $this->assertEquals('from@enhavo.com', $from);
            $this->assertEquals('enhavo', $senderName);
            $this->assertEquals('admin@enhavo.com', $to);
            $this->assertEquals('subscriber.mail.admin.subject.trans', $subject);
            $this->assertEquals('__ATPL__', $template);
            $this->assertInstanceOf(SubscriberInterface::class, $options['subscriber']);

            return new Message();
        });

        /** @var SubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $subscriber->method('getSubscription')->willReturn('default');

        $strategyType = new DoubleOptInStrategyType($dependencies->newsletterManager, $dependencies->pendingManager, $dependencies->router);
        $strategyType->setTranslator($dependencies->translator);

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'activate_route' => '__ROUTE_NAME__',
            'activate_route_parameters' => [],
            'admin_template' => '__ATPL__',
            'template' => '__TPL__',
            'confirmation_template' => '__CTPL__',
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
            'translation_domain' => '',
            'notify_admin' => true,
            'confirm' => false,
        ]);
        $strategy->setStorage($dependencies->storage);
        $strategy->activateSubscriber($subscriber);
    }

    public function testExists()
    {
        $dependencies = $this->createDependencies();
        $dependencies->pendingManager->expects($this->exactly(2))->method('findOneBy')->willReturnCallback(function ($email, $subscription) {
            $this->assertEquals('to@enhavo.com', $email);
            $this->assertRegExp('/(default|missing)/', $subscription);

            return ($subscription === 'default' ? new PendingSubscriber() : null);
        });
        $dependencies->storage->expects($this->once())->method('exists')->willReturn(true);

        /** @var SubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriber->expects($this->once())->method('getEmail')->willReturn('to@enhavo.com');
        $subscriber->expects($this->once())->method('getSubscription')->willReturn('default');

        $strategyType = new DoubleOptInStrategyType($dependencies->newsletterManager, $dependencies->pendingManager, $dependencies->router);

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'activate_route' => '__ROUTE_NAME__',
            'activate_route_parameters' => [],
            'template' => '__TPL__',
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
            'admin_subject' => '__SUBJECT__',
            'translation_domain' => ''

        ]);
        $strategy->setStorage($dependencies->storage);
        $this->assertEquals(true, $strategy->exists($subscriber));

        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriber->expects($this->once())->method('getEmail')->willReturn('to@enhavo.com');
        $subscriber->expects($this->once())->method('getSubscription')->willReturn('missing');

        $this->assertEquals(true, $strategy->exists($subscriber));

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'activate_route' => '__ROUTE_NAME__',
            'activate_route_parameters' => [],
            'template' => '__TPL__',
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
            'admin_subject' => '__SUBJECT__',
            'translation_domain' => '',
            'check_exists' => false,

        ]);
        $strategy->setStorage($dependencies->storage);
        $this->assertEquals(false, $strategy->exists($subscriber));
    }
}

class StrategyDoubleOptInTypeTestDependencies
{
    /** @var NewsletterManager|MockObject */
    public $newsletterManager;

    /** @var PendingSubscriberManager|MockObject */
    public $pendingManager;

    /** @var RouterInterface|MockObject */
    public $router;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var Storage|MockObject */
    public $storage;

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;
}
