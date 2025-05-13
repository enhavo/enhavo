<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Strategy;

use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\NotifyStrategyType;
use Enhavo\Bundle\NewsletterBundle\Strategy\Type\StrategyType;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class StrategyNotifyTypeTest extends TestCase
{
    private function createDependencies(): StrategyNotifyTypeTestDependencies
    {
        $dependencies = new StrategyNotifyTypeTestDependencies();
        $dependencies->newsletterManager = $this->getMockBuilder(NewsletterManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            return $message.'.trans';
        });
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
        $dependencies->newsletterManager->expects($this->exactly(2))->method('createMessage')->willReturnCallback(function ($from, $senderName, $to, $subject, $template, $options) {
            $this->assertEquals('from@enhavo.com', $from);
            $this->assertEquals('enhavo', $senderName);
            $this->assertMatchesRegularExpression('/(admin|to)@enhavo.com/', $to);

            if ('admin@enhavo.com' === $to) {
                $this->assertEquals('subscriber.mail.admin.subject.trans', $subject);
                $this->assertEquals('__ATPL__', $template);
            } else {
                $this->assertEquals('__NTPL__', $template);
                $this->assertEquals('subscriber.mail.notify.subject.trans', $subject);
            }

            $this->assertInstanceOf(SubscriberInterface::class, $options['subscriber']);

            return new Message();
        });
        $dependencies->eventDispatcher->expects($this->exactly(2))->method('dispatch')->willReturnCallback(function ($event, $key) {
            $this->assertInstanceOf(SubscriberEvent::class, $event);
            $this->assertInstanceOf(SubscriberInterface::class, $event->getSubscriber());

            return $event;
        });

        /** @var SubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();
        $subscriber->method('getEmail')->willReturn('to@enhavo.com');
        $subscriber->method('setCreatedAt')->willReturnCallback(function (\DateTime $date): void {
        });

        $strategyType = new NotifyStrategyType($dependencies->newsletterManager);
        $strategyType->setTranslator($dependencies->translator);

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'template' => '__NTPL__',
            'admin_template' => '__ATPL__',
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
        ]);
        $strategy->setStorage($dependencies->storage);
        $this->assertEquals('subscriber.form.message.notify', $strategy->addSubscriber($subscriber));
    }

    public function testExists()
    {
        $dependencies = $this->createDependencies();
        $dependencies->storage->expects($this->exactly(1))->method('exists')->willReturn(true);

        /** @var SubscriberInterface|MockObject $subscriber */
        $subscriber = $this->getMockBuilder(SubscriberInterface::class)->getMock();

        $strategyType = new NotifyStrategyType($dependencies->newsletterManager);

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
            'from' => 'from@enhavo.com',
            'sender_name' => 'enhavo',
            'admin_email' => 'admin@enhavo.com',
        ]);
        $strategy->setStorage($dependencies->storage);
        $this->assertEquals(true, $strategy->exists($subscriber));

        $strategy = $this->createInstance($strategyType, [new StrategyType($dependencies->translator, $dependencies->eventDispatcher)], [
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

class StrategyNotifyTypeTestDependencies
{
    /** @var NewsletterManager|MockObject */
    public $newsletterManager;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var Storage|MockObject */
    public $storage;

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;
}
