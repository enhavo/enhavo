<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-03
 * Time: 10:24
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Newsletter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Entity\Receiver;
use Enhavo\Bundle\NewsletterBundle\Exception\SendException;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Enhavo\Bundle\NewsletterBundle\Newsletter\ParameterParser;
use Enhavo\Bundle\NewsletterBundle\Provider\ProviderInterface;
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
    private function createDependencies()
    {
        $dependency = new DependencyProvider();
        $dependency->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependency->mailerManager = $this->getMockBuilder(MailerManager::class)->disableOriginalConstructor()->getMock();
        $dependency->twig = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $dependency->twig->method('render')->willReturnCallback(function ($template) {
            return $template.'.rendered';
        });
        $dependency->from = 'from@enhavo.com';
        $dependency->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->disableOriginalConstructor()->getMock();
        $dependency->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();
        $dependency->parameterParser = $this->getMockBuilder(ParameterParser::class)->disableOriginalConstructor()->getMock();
        $dependency->parameterParser->method('parse')->willReturnCallback(function ($content) {
            return $content .'.parsed';
        });
        $dependency->templateManager = $this->getMockBuilder(TemplateManager::class)->disableOriginalConstructor()->getMock();
        $dependency->templateManager->method('getTemplate')->willReturnCallback(function ($key) {
            return $key .'.managed';
        });

        $providerMock = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $providerMock->method('getTestReceivers')->willReturn([
            $this->createTestReceiver()
        ]);
        $dependency->provider = $providerMock;

        return $dependency;
    }

    private function createInstance(DependencyProvider $dependency, array $templates = [])
    {
        return new NewsletterManager(
            $dependency->em,
            $dependency->mailerManager,
            $dependency->tokenGenerator,
            $dependency->logger,
            $dependency->twig,
            $dependency->templateManager,
            $dependency->parameterParser,
            $dependency->provider,
            $dependency->from,
            $templates
        );
    }

    private function createTestReceiver()
    {
        $receiver = new Receiver();
        $receiver->setEmail('test@test.de');
        $receiver->setToken('__TRACKING_TOKEN__');
        $receiver->setParameters([
            'firstname' => 'Harry',
            'lastname' => 'Hirsch',
            'token' =>  '__ID_TOKEN__'
        ]);
        return $receiver;
    }


    private function createDummyNewsletter(): Newsletter
    {
        $newsletter = new Newsletter();
        $newsletter->setTemplate('default');
        $newsletter->setSubject('subject');

        return$newsletter;
    }

    /**
     * @throws SendException
     */
    public function testAlreadyPrepared()
    {
        $newsletterManager = $this->createInstance($this->createDependencies());

        $newsletter = $this->createDummyNewsletter();
        $newsletter->setState(Newsletter::STATE_PREPARED);

        $this->expectException(SendException::class);
        $newsletterManager->prepare($newsletter);
    }

    public function testPrepare()
    {
        $receiverOne = new Receiver();
        $receiverOne->setToken('alreadyHasAToken');

        $receiverTwo = new Receiver();
        $dependency = $this->createDependencies();

        $providerMock = $this->getMockBuilder(ProviderInterface::class)->getMock();
        $providerMock->method('getReceivers')->willReturn([
            $receiverOne, $receiverTwo
        ]);
        $dependency->provider = $providerMock;


        $dependency->tokenGenerator->method('generateToken')->willReturn('aGeneratedToken');
        $dependency->em->expects($this->once())->method('flush');

        $newsletterManager = $this->createInstance($dependency);

        $newsletter = $this->createDummyNewsletter();
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
        $dependency = $this->createDependencies();
        $dependency->mailerManager->method('createMessage')->willReturn(new Message());
        $dependency->mailerManager->method('sendMessage');
        $newsletterManager = $this->createInstance($dependency, ['default' => [
            'template' => 'template.html.twig',
        ]]);

        $receiverOne = new Receiver();
        $receiverOne->setEmail('peter@pan.de');
        $receiverTwo = new Receiver();
        $receiverTwo->setEmail('james@bond.com');

        $newsletter = $newsletter = $this->createDummyNewsletter();
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

        $dependency = $this->createDependencies();
        $dependency->mailerManager->method('createMessage')->willReturn(new Message());
        $dependency->mailerManager->method('sendMessage')->willReturnCallback(function($message) use ($messageContainer) {
            $messageContainer->message = $message;
        });
        $newsletterManager = $this->createInstance($dependency, ['other' => [
            'template' => 'template.html.twig',
        ]]);

        $receiver = new Receiver();
        $receiver->setEmail('james@bond.com');

        $content = new Content('attachmentContent');
        $file = new File();
        $file->setContent($content);

        $newsletter = $this->createDummyNewsletter();
        $newsletter->setTemplate('other');
        $newsletter->addAttachment($file);
        $newsletter->setState(Newsletter::STATE_PREPARED);
        $newsletter->addReceiver($receiver);

        $newsletterManager->send($newsletter, 1);

        $this->assertCount(1, $messageContainer->message->getAttachments());
        /** @var File $attachment */
        $attachment = $messageContainer->message->getAttachments()[0];
        $this->assertEquals('attachmentContent', $attachment->getFile()->getContent()->getContent());
    }

    public function testFinishSending()
    {
        $dependency = $this->createDependencies();
        $dependency->mailerManager->method('createMessage')->willReturn(new Message());
        $dependency->mailerManager->method('sendMessage');
        $newsletterManager = $this->createInstance($dependency, ['default' => [
            'template' => 'template.html.twig',
        ]]);

        $receiverOne = new Receiver();
        $receiverOne->setEmail('peter@pan.de');
        $receiverOne->setSentAt(new \DateTime());
        $receiverTwo = new Receiver();
        $receiverTwo->setEmail('james@bond.com');

        $newsletter = $this->createDummyNewsletter();
        $newsletter->setState(Newsletter::STATE_PREPARED);
        $newsletter->addReceiver($receiverOne);
        $newsletter->addReceiver($receiverTwo);

        $newsletterManager->send($newsletter, 1);

        $this->assertEquals(Newsletter::STATE_SENT, $newsletter->getState());
        $this->assertTrue($receiverTwo->isSent());
    }


    public function testSendNewsletterPrepared()
    {
        $dependency = $this->createDependencies();
        $newsletterManager = $this->createInstance($dependency);

        $newsletter = $this->createDummyNewsletter();
        $newsletter->setTemplate('other');
        $newsletter->setState(Newsletter::STATE_CREATED);
        $this->expectException(SendException::class);
        $newsletterManager->send($newsletter);

    }

    public function testSendNewsletterAlreadySent()
    {
        $dependency = $this->createDependencies();
        $newsletterManager = $this->createInstance($dependency);

        $newsletter = $this->createDummyNewsletter();
        $newsletter->setTemplate('other');
        $newsletter->setState(Newsletter::STATE_SENT);
        $this->expectException(SendException::class);
        $newsletterManager->send($newsletter);

    }

    public function testRender()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parameterParser->method('parse')->willReturnCallback(function ($content) { return $content; });
        $newsletterManager = $this->createInstance($dependencies, ['default' => [
            'template' => 'tpl.html.twig',
        ]]);

        $newsletter = new Newsletter();
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);
        $receiver->setParameters(['param1' => 'someValue']);

        $content = $newsletterManager->render($receiver);
        $this->assertEquals('tpl.html.twig.managed.rendered.parsed', $content);
    }

    public function testWithTemplateKey()
    {
        $dependencies = $this->createDependencies();
        $dependencies->twig->method('render')->willReturnCallback(function ($template, $parameters) { return $template; });
        $dependencies->templateManager->method('getTemplate')->willReturnCallback(function ($template) { return $template; });
        $newsletterRenderer = $this->createInstance($dependencies, ['other' => [
            'template' => 'pathToOtherTemplate.twig',
            'label' => 'Other Template'
        ]]);

        $newsletter = new Newsletter();
        $newsletter->setTemplate('other');
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $content = $newsletterRenderer->render($receiver);
        $this->assertEquals('pathToOtherTemplate.twig.managed.rendered', $content);
    }

    public function testWithoutTemplateKey()
    {
        $dependencies = $this->createDependencies();
        $newsletterRenderer = $this->createInstance($dependencies);

        $newsletter = new Newsletter();
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $this->expectException(\Exception::class);
        $newsletterRenderer->render($receiver);
    }

    public function testRenderPreview()
    {
        $dependency = $this->createDependencies();
        $newsletterManager = $this->createInstance($dependency, ['default' => [
            'template' => 'template.html.twig',
        ]]);

        $newsletter = $this->createDummyNewsletter();
        $content = $newsletterManager->renderPreview($newsletter);

        $this->assertEquals('template.html.twig.managed.rendered.parsed', $content);
    }

    public function testSendTest()
    {
        $messageContainer = new MessageContainer();
        $newsletter = $this->createDummyNewsletter();
        $newsletter->setTemplate('other');
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $dependency = $this->createDependencies();
        $dependency->mailerManager->method('createMessage')->willReturn(new Message());
        $dependency->mailerManager->method('sendMessage')->willReturnCallback(function($message) use ($messageContainer) {
            $messageContainer->message = $message;
            return 1;
        });
        $newsletterManager = $this->createInstance($dependency, ['default' => [
            'template' => 'template.html.twig',
        ]]);

        $newsletter = $this->createDummyNewsletter();
        $newsletterManager->sendTest($newsletter, 'peter@pan.de');

        $this->assertEquals('template.html.twig', $messageContainer->message->getTemplate());
        $this->assertEquals('from@enhavo.com', $messageContainer->message->getFrom());
        $this->assertEquals('peter@pan.de', $messageContainer->message->getTo());
        $this->assertEquals('subject', $messageContainer->message->getSubject());
        $this->assertEquals('text/html', $messageContainer->message->getContentType());
        $this->assertInstanceOf(Receiver::class, $messageContainer->message->getContext()['receiver']);
        $this->assertInstanceOf(Newsletter::class, $messageContainer->message->getContext()['resource']);
    }


    public function testSendTestNotSent()
    {
        $messageContainer = new MessageContainer();
        $newsletter = $this->createDummyNewsletter();
        $newsletter->setTemplate('other');
        $receiver = new Receiver();
        $receiver->setNewsletter($newsletter);

        $dependency = $this->createDependencies();
        $dependency->mailerManager->method('createMessage')->willReturn(new Message());
        $dependency->mailerManager->method('sendMessage')->willReturnCallback(function($message) use ($messageContainer) {
            $messageContainer->message = $message;
            return 0;
        });
        $newsletterManager = $this->createInstance($dependency, ['default' => [
            'template' => 'template.html.twig',
        ]]);

        $newsletter = $this->createDummyNewsletter();
        $newsletterManager->sendTest($newsletter, 'peter@pan.de');

        $this->assertEquals('template.html.twig', $messageContainer->message->getTemplate());
        $this->assertEquals('from@enhavo.com', $messageContainer->message->getFrom());
        $this->assertEquals('peter@pan.de', $messageContainer->message->getTo());
        $this->assertEquals('subject', $messageContainer->message->getSubject());
        $this->assertEquals('text/html', $messageContainer->message->getContentType());
        $this->assertInstanceOf(Receiver::class, $messageContainer->message->getContext()['receiver']);
        $this->assertInstanceOf(Newsletter::class, $messageContainer->message->getContext()['resource']);
    }
}

class DependencyProvider
{
    /** @var EntityManagerInterface|MockObject */
    public $em;
    /** @var MailerManager|MockObject */
    public $mailerManager;
    /** @var string */
    public $from;
    /** @var TokenGeneratorInterface|MockObject */
    public $tokenGenerator;
    /** @var LoggerInterface|MockObject */
    public $logger;
    /** @var SubscriptionManager|MockObject */
    public $twig;
    /** @var TemplateManager|MockObject */
    public $templateManager;
    /** @var ParameterParser|MockObject */
    public $parameterParser;
    /** @var ProviderInterface */
    public $provider;
}

class MessageContainer
{
    /** @var Message */
    public $message;
}
