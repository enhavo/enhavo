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
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterRenderer;
use Enhavo\Bundle\NewsletterBundle\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class NewsletterManagerTest extends TestCase
{
    private function createDependency()
    {
        $dependency = new DependencyProvider();
        $dependency->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependency->mailer = $this->getMockBuilder(\Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $dependency->from = 'test@email.de';
        $dependency->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->disableOriginalConstructor()->getMock();
        $dependency->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $dependency->provider = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $dependency->renderer = $this->getMockBuilder(NewsletterRenderer::class)->disableOriginalConstructor()->getMock();
        return $dependency;
    }

    private function createNewsletterManager(DependencyProvider $dependency)
    {
        return new NewsletterManager(
            $dependency->em,
            $dependency->mailer,
            $dependency->from,
            $dependency->tokenGenerator,
            $dependency->logger,
            $dependency->provider,
            $dependency->renderer
        );
    }

    /**
     * @expectedException \Enhavo\Bundle\NewsletterBundle\Exception\SendException
     */
    public function testAlreadyPrepared()
    {
        $newsletterManager = $this->createNewsletterManager($this->createDependency());

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_PREPARED);

        $newsletterManager->prepare($newsletter);
    }

    public function testPrepare()
    {
        $receiverOne = new Receiver();
        $receiverOne->setToken('alreadyHasAToken');

        $receiverTwo = new Receiver();

        $dependency = $this->createDependency();
        $dependency->provider->method('getReceivers')->willReturn([$receiverOne, $receiverTwo]);
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

    /**
     * @expectedException \Enhavo\Bundle\NewsletterBundle\Exception\SendException
     */
    public function sendNewsletterPrepared()
    {
        $dependency = $this->createDependency();
        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_CREATED);
        $newsletterManager->send($newsletter);
    }

    /**
     * @expectedException \Enhavo\Bundle\NewsletterBundle\Exception\SendException
     */
    public function sendNewsletterAlreadySent()
    {
        $dependency = $this->createDependency();
        $newsletterManager = $this->createNewsletterManager($dependency);

        $newsletter = new Newsletter();
        $newsletter->setState(Newsletter::STATE_SENT);
        $newsletterManager->send($newsletter);
    }
}

class DependencyProvider
{
    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $em;
    /** @var \Swift_Mailer|\PHPUnit_Framework_MockObject_MockObject */
    public $mailer;
    public $from;
    /** @var TokenGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $tokenGenerator;
    public $logger;
    /** @var ProviderInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $provider;
    /** @var NewsletterRenderer|\PHPUnit_Framework_MockObject_MockObject */
    public $renderer;
}
