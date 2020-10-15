<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-03
 * Time: 10:24
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\DeliveryException;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterRenderer;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\Strategy;
use Enhavo\Bundle\NewsletterBundle\Subscription\Subscription;
use Enhavo\Bundle\NewsletterBundle\Subscription\SubscriptionManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Twig\Environment;

class NewsletterManagerTest extends TestCase
{
    private function createDependency()
    {
        $dependency = new DependencyProvider();
        $dependency->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependency->mailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $dependency->subscriptionManager = $this->getMockBuilder(SubscriptionManager::class)->disableOriginalConstructor()->getMock();
        $dependency->twig = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $dependency->from = 'from@enhavo.com';
        $dependency->testReceiver = [
            'token' => '__TRACKING_TOKEN__',
            'parameters' => [
                'firstname' => 'Harry',
                'lastname' => 'Hirsch',
                'token' => '__ID_TOKEN__'
            ]
        ];
        $dependency->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->disableOriginalConstructor()->getMock();
        $dependency->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $dependency->renderer = $this->getMockBuilder(NewsletterRenderer::class)->disableOriginalConstructor()->getMock();

        return $dependency;
    }

    private function createNewsletterManager(DependencyProvider $dependency)
    {
        return new NewsletterManager(
            $dependency->em,
            $dependency->mailer,
            $dependency->subscriptionManager,
            $dependency->tokenGenerator,
            $dependency->logger,
            $dependency->renderer,
            $dependency->twig,
            $dependency->from,
            $dependency->testReceiver
        );
    }

    /**
     * @throws DeliveryException
     */
    public function testAlreadyPrepared()
    {
        $newsletterManager = $this->createNewsletterManager($this->createDependency());

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_PREPARED);

        $this->expectException(DeliveryException::class);
        $newsletterManager->prepare($newsletter);
    }

    public function testPrepare()
    {
        $receiverOne = new Receiver();
        $receiverOne->setToken('alreadyHasAToken');

        $receiverTwo = new Receiver();
        $dependency = $this->createDependency();

        $subscription = $this->getMockBuilder(Subscription::class)->disableOriginalConstructor()->getMock();
        $subscription->method('getStrategy')->willReturnCallback(function () use ($receiverOne, $receiverTwo) {
            $strategy = $this->getMockBuilder(Strategy::class)->disableOriginalConstructor()->getMock();
            $storage = $this->getMockBuilder(Storage::class)->disableOriginalConstructor()->getMock();
            $storage->method('getReceivers')->willReturn([$receiverOne, $receiverTwo]);
            $strategy->method('getStorage')->willReturn($storage);
            return $strategy;
        });

        $dependency->subscriptionManager->method('getSubscription')->willReturnCallback(function ($key) use ($subscription) {
            return $subscription;
        });

        $dependency->tokenGenerator->method('generateToken')->willReturn('aGeneratedToken');
        $dependency->em->expects($this->once())->method('flush');

        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_CREATED);
        $newsletter->setStartAt(null);

        $newsletterManager->prepare($newsletter);

        $this->assertEquals(Newsletter::STATE_PREPARED, $newsletter->getState());
        $this->assertEquals('alreadyHasAToken', $receiverOne->getToken());
        $this->assertEquals('aGeneratedToken', $receiverTwo->getToken());
        $this->assertInstanceOf(\DateTime::class, $newsletter->getStartAt());
    }

    public function testSending()
    {
        $dependency = $this->createDependency();
        $dependency->mailer->method('createMessage')->willReturn(new \Swift_Message());
        $dependency->mailer->method('send')->willReturn(true);
        $dependency->renderer->method('render')->willReturn('Newsletter Content');
        $newsletterManager = $this->createNewsletterManager($dependency);

        $receiverOne = new Receiver();
        $receiverOne->setEmail('peter@pan.de');
        $receiverTwo = new Receiver();
        $receiverTwo->setEmail('james@bond.com');

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_PREPARED);
        $newsletter->addReceiver($receiverOne);
        $newsletter->addReceiver($receiverTwo);

        $newsletterManager->send($newsletter, 1);

        $this->assertEquals(Newsletter::STATE_SENDING, $newsletter->getState());
        $this->assertTrue($receiverOne->isSent());
        $this->assertFalse($receiverTwo->isSent());
    }

    public function testSendAttachments()
    {
        $messageContainer = new MessageContainer();

        $dependency = $this->createDependency();
        $dependency->mailer->method('createMessage')->willReturn(new \Swift_Message());
        $dependency->mailer->method('send')->willReturnCallback(function($message) use ($messageContainer) {
            $messageContainer->message = $message;
            return true;
        });
        $newsletterManager = $this->createNewsletterManager($dependency);

        $receiver = new Receiver();
        $receiver->setEmail('james@bond.com');

        $content = new Content('attachmentContent');
        $file = new File();
        $file->setContent($content);

        $newsletter = new Newsletter();
        $newsletter->addAttachment($file);
        $newsletter->setState(Newsletter::STATE_PREPARED);
        $newsletter->addReceiver($receiver);

        $newsletterManager->send($newsletter, 1);

        $this->assertCount(1, $messageContainer->message->getChildren());
        $this->assertEquals('attachmentContent', $messageContainer->message->getChildren()[0]->getBody());
    }

    public function testFinishSending()
    {
        $dependency = $this->createDependency();
        $dependency->mailer->method('createMessage')->willReturn(new \Swift_Message());
        $dependency->mailer->method('send')->willReturn(true);
        $dependency->renderer->method('render')->willReturn('Newsletter Content');
        $newsletterManager = $this->createNewsletterManager($dependency);

        $receiverOne = new Receiver();
        $receiverOne->setEmail('peter@pan.de');
        $receiverOne->setSentAt(new \DateTime());
        $receiverTwo = new Receiver();
        $receiverTwo->setEmail('james@bond.com');

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_PREPARED);
        $newsletter->addReceiver($receiverOne);
        $newsletter->addReceiver($receiverTwo);

        $newsletterManager->send($newsletter, 1);

        $this->assertEquals(Newsletter::STATE_SENT, $newsletter->getState());
        $this->assertTrue($receiverTwo->isSent());
    }


    public function testSendNewsletterPrepared()
    {
        $dependency = $this->createDependency();
        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_CREATED);
        $this->expectException(DeliveryException::class);
        $newsletterManager->send($newsletter);

    }

    public function testSendNewsletterAlreadySent()
    {
        $dependency = $this->createDependency();
        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_SENT);
        $this->expectException(DeliveryException::class);
        $newsletterManager->send($newsletter);

    }

    public function testRender()
    {
        $dependency = $this->createDependency();
        $dependency->renderer->method('render')->willReturn('content');
        $newsletterManager = $this->createNewsletterManager($dependency);

        $receiverOne = new Receiver();
        $content = $newsletterManager->render($receiverOne);

        $this->assertEquals('content', $content);
    }

    public function testRenderPreview()
    {
        $dependency = $this->createDependency();
        $dependency->renderer->method('render')->willReturn('content');
        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $content = $newsletterManager->renderPreview($newsletter);

        $this->assertEquals('content', $content);
    }

    public function testSendTest()
    {
        $messageContainer = new MessageContainer();
        $newsletter = new Newsletter();
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $dependency = $this->createDependency();
        $dependency->renderer->method('render')->willReturn('content');
        $dependency->mailer->method('createMessage')->willReturn(new \Swift_Message());
        $dependency->mailer->method('send')->willReturnCallback(function($message) use ($messageContainer) {
            $messageContainer->message = $message;
            return true;
        });
        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $newsletterManager->sendTest($newsletter, 'peter@pan.de');

        $this->assertEquals('content', $messageContainer->message->getBody());
    }
}

class DependencyProvider
{
    /** @var EntityManagerInterface|MockObject */
    public $em;
    /** @var \Swift_Mailer|MockObject */
    public $mailer;
    /** @var string */
    public $from;
    /** @var TokenGeneratorInterface|MockObject */
    public $tokenGenerator;
    /** @var LoggerInterface|MockObject */
    public $logger;
    /** @var NewsletterRenderer|MockObject */
    public $renderer;
    /** @var SubscriptionManager|MockObject */
    public $subscriptionManager;
    /** @var Environment|MockObject */
    public $twig;
    /** @var array */
    public $testReceiver;
}

class MessageContainer
{
    /** @var \Swift_Message */
    public $message;
}
