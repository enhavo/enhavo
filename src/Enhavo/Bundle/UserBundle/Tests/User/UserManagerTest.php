<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Tests\User;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Mailer\Defaults;
use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationConfirmConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\Registration\RegistrationRegisterConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordRequestConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderInterface;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderResolver;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author blutze
 */
class UserManagerTest extends TestCase
{
    private function createInstance(UserManagerTestDependencies $dependencies, ?array $mapping = null)
    {
        return new UserManager(
            $dependencies->entityManager,
            $dependencies->mailerManager,
            $dependencies->resolver,
            $dependencies->tokenGenerator,
            $dependencies->translator,
            $dependencies->userPasswordHasher,
            $dependencies->router,
            $dependencies->eventDispatcher,
            $dependencies->tokenStorage,
            $dependencies->requestStack,
            $dependencies->sessionStrategy,
            $dependencies->userChecker,
            $dependencies->defaultFirewall
        );
    }

    private function createDependencies(): UserManagerTestDependencies
    {
        $dependencies = new UserManagerTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->mailerManager = $this->getMockBuilder(MailerManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->mailerManager->method('getDefaults')->willReturn(new Defaults('from@enhavo.com', 'enhavo', 'to@enhavo.com'));
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            return $message.'.translated';
        });
        $dependencies->userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->userPasswordHasher = $this->getMockBuilder(UserPasswordHasherInterface::class)->getMock();
        $dependencies->userPasswordHasher->method('hashPassword')->willReturnCallback(function ($user, $password) {
            return $password.'.hashed';
        });
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->message = $this->getMockBuilder(Message::class)->getMock();

        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $dependencies->sessionStrategy = $this->getMockBuilder(SessionAuthenticationStrategyInterface::class)->getMock();
        $dependencies->defaultFirewall = 'main';
        $dependencies->resolver = $this->getMockBuilder(UserIdentifierProviderResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->resolver->method('getProvider')->willReturn(new CustomerIdUserIdentifierProvider());

        return $dependencies;
    }

    public function testRegister()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('user@enhavo.com');
        $user->setPlainPassword('password');

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            /* @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::CREATED, $name);
            $this->assertEquals($user, $event->getUser());

            return $user;
        });
        $dependencies->tokenGenerator->expects($this->once())->method('generateToken')->willReturn('__TOKEN__');
        $dependencies->mailerManager->expects($this->once())->method('createMessage')->willReturn($dependencies->message);
        $dependencies->mailerManager->expects($this->once())->method('sendMessage');
        $dependencies->message->method('setSubject')->willReturnCallback(function ($subject): void {
            $this->assertEquals('mail.subject.translated', $subject);
        });
        $dependencies->message->method('setTemplate')->willReturnCallback(function ($subject): void {
            $this->assertEquals('mail.html.twig', $subject);
        });
        $dependencies->message->method('setTo')->willReturnCallback(function ($subject): void {
            $this->assertEquals('user@enhavo.com', $subject);
        });
        $dependencies->message->method('setFrom')->willReturnCallback(function ($subject): void {
            $this->assertEquals('admin@enhavo.com', $subject);
        });
        $dependencies->message->method('setSenderName')->willReturnCallback(function ($subject): void {
            $this->assertEquals('Enhavo.translated', $subject);
        });
        $dependencies->message->method('setContentType')->willReturnCallback(function ($subject): void {
            $this->assertEquals('content/type', $subject);
        });

        $configuration = new RegistrationRegisterConfiguration();
        $configuration->setMailTemplate('mail.html.twig');
        $configuration->setMailSubject('mail.subject');
        $configuration->setConfirmationRoute('confirmation.route');
        $configuration->setTranslationDomain('EnhavoUserBundle');
        $configuration->setMailFrom('admin@enhavo.com');
        $configuration->setMailSenderName('Enhavo');
        $configuration->setMailContentType('content/type');

        $manager = $this->createInstance($dependencies);
        $manager->register($user, $configuration);

        $this->assertEquals('1337.user@enhavo.com', $user->getUserIdentifier());
        $this->assertEquals('password.hashed', $user->getPassword());
        $this->assertNull($user->getPlainPassword());
        $this->assertEquals('__TOKEN__', $user->getConfirmationToken());
        $this->assertFalse($user->isEnabled());
    }

    public function testConfirm()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('user@enhavo.com');
        $user->setPlainPassword('password');
        $user->setConfirmationToken('__TOKEN__');
        $user->setEnabled(false);

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            /* @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::REGISTRATION_CONFIRMED, $name);
            $this->assertEquals($user, $event->getUser());

            return $event;
        });
        $dependencies->mailerManager->expects($this->once())->method('createMessage')->willReturn($dependencies->message);
        $dependencies->mailerManager->expects($this->once())->method('sendMessage');
        $dependencies->message->method('setSubject')->willReturnCallback(function ($subject): void {
            $this->assertEquals('mail.subject.translated', $subject);
        });
        $dependencies->message->method('setTemplate')->willReturnCallback(function ($subject): void {
            $this->assertEquals('mail.html.twig', $subject);
        });
        $dependencies->message->method('setTo')->willReturnCallback(function ($subject): void {
            $this->assertEquals('user@enhavo.com', $subject);
        });
        $dependencies->message->method('setFrom')->willReturnCallback(function ($subject): void {
            $this->assertEquals('from@enhavo.com', $subject);
        });
        $dependencies->message->method('setSenderName')->willReturnCallback(function ($subject): void {
            $this->assertEquals('enhavo.translated', $subject);
        });
        $dependencies->message->method('setContentType')->willReturnCallback(function ($subject): void {
            $this->assertEquals('content/type', $subject);
        });

        $manager = $this->createInstance($dependencies);

        $configuration = new RegistrationConfirmConfiguration();
        $configuration->setMailTemplate('mail.html.twig');
        $configuration->setMailSubject('mail.subject');
        $configuration->setTranslationDomain('EnhavoUserBundle');
        $configuration->setMailContentType('content/type');

        $manager->confirm($user, $configuration);

        $this->assertEquals('1337.user@enhavo.com', $user->getUserIdentifier());
        $this->assertEquals('password.hashed', $user->getPassword());
        $this->assertNull($user->getPlainPassword());
        $this->assertNull($user->getConfirmationToken());
        $this->assertTrue($user->isEnabled());
    }

    public function testResetPassword()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('user@enhavo.com');
        $user->setPlainPassword('password');

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            /* @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::PASSWORD_RESET_REQUESTED, $name);
            $this->assertEquals($user, $event->getUser());

            return $user;
        });
        $dependencies->tokenGenerator->expects($this->once())->method('generateToken')->willReturn('__TOKEN__');
        $dependencies->mailerManager->expects($this->once())->method('createMessage')->willReturn($dependencies->message);
        $dependencies->mailerManager->expects($this->once())->method('sendMessage');
        $dependencies->message->method('setSubject')->willReturnCallback(function ($subject): void {
            $this->assertEquals('mail.subject.translated', $subject);
        });
        $dependencies->message->method('setTemplate')->willReturnCallback(function ($subject): void {
            $this->assertEquals('mail.html.twig', $subject);
        });
        $dependencies->message->method('setTo')->willReturnCallback(function ($subject): void {
            $this->assertEquals('user@enhavo.com', $subject);
        });
        $dependencies->message->method('setFrom')->willReturnCallback(function ($subject): void {
            $this->assertEquals('admin@enhavo.com', $subject);
        });
        $dependencies->message->method('setSenderName')->willReturnCallback(function ($subject): void {
            $this->assertEquals('Enhavo.translated', $subject);
        });
        $dependencies->message->method('setContentType')->willReturnCallback(function ($subject): void {
            $this->assertEquals('content/type', $subject);
        });

        $manager = $this->createInstance($dependencies);

        $configuration = new ResetPasswordRequestConfiguration();
        $configuration->setMailTemplate('mail.html.twig');
        $configuration->setMailSubject('mail.subject');
        $configuration->setMailFrom('admin@enhavo.com');
        $configuration->setMailSenderName('Enhavo');
        $configuration->setMailContentType('content/type');
        $configuration->setTranslationDomain('EnhavoUserBundle');
        $configuration->setConfirmationRoute('confirmation.route');

        $manager->resetPassword($user, $configuration);

        $this->assertEquals('1337.user@enhavo.com', $user->getUserIdentifier());
        $this->assertEquals('password.hashed', $user->getPassword());
        $this->assertNull($user->getPlainPassword());
        $this->assertEquals('__TOKEN__', $user->getConfirmationToken());
    }

    public function testChangePassword()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('user@enhavo.com');
        $user->setPlainPassword('password');

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            /* @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::PASSWORD_CHANGED, $name);
            $this->assertEquals($user, $event->getUser());

            return $event;
        });

        $manager = $this->createInstance($dependencies);

        $manager->changePassword($user);
        $this->assertEquals('password.hashed', $user->getPassword());
        $this->assertNull($user->getConfirmationToken());
    }

    public function testChangeEmail()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('old@mail.com');
        $user->setPlainPassword('password');

        $dependencies = $this->createDependencies();
        $dependencies->mailerManager->expects($this->exactly(1))->method('createMessage')->willReturnCallback(function () {
            return new Message();
        });
        $dependencies->mailerManager->expects($this->once())->method('sendMessage')->willReturnCallback(function (Message $message) {
            $this->assertEquals('admin@enhavo.com', $message->getFrom());
            $this->assertEquals('change-email.subject.translated', $message->getSubject());
            $this->assertEquals('change-email.html.twig', $message->getTemplate());

            return 1;
        });
        $dependencies->entityManager->expects($this->never())->method('persist');
        $dependencies->entityManager->expects($this->once())->method('flush');

        $manager = $this->createInstance($dependencies);

        $configuration = new ChangeEmailConfirmConfiguration();
        $configuration->setMailTemplate('change-email.html.twig');
        $configuration->setMailSubject('change-email.subject');
        $configuration->setMailContentType('content/type');
        $configuration->setMailFrom('admin@enhavo.com');
        $configuration->setTranslationDomain('EnhavoUserBundle');
        $configuration->setConfirmationRoute('confirmation.route');

        $manager->changeEmail($user, 'new@mail.com', $configuration);

        $this->assertEquals('new@mail.com', $user->getEmail());
        $this->assertEquals('1337.new@mail.com', $user->getUserIdentifier());
    }

    public function testActivate()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('activate@enhavo.com');
        $user->setEnabled(false);
        $user->setConfirmationToken('token');
        $user->setPlainPassword('');

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            /* @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::ACTIVATED, $name);
            $this->assertEquals($user, $event->getUser());

            return $event;
        });
        $dependencies->entityManager->expects($this->never())->method('persist');
        $dependencies->entityManager->expects($this->once())->method('flush');

        $manager = $this->createInstance($dependencies, []);

        $manager->activate($user);

        $this->assertTrue($user->isEnabled());
        $this->assertNull($user->getConfirmationToken());
    }

    public function testLogin()
    {
        $user = new UserMock();
        $user->addRole(UserInterface::ROLE_DEFAULT);
        $user->setCustomerId('1337');
        $user->setEmail('activate@enhavo.com');
        $user->setEnabled(true);

        $request = new Request();

        $dependencies = $this->createDependencies();
        $dependencies->tokenStorage->expects($this->once())->method('setToken')->willReturnCallback(function ($token): void {
            $this->assertInstanceOf(UsernamePasswordToken::class, $token);
        });
        $dependencies->sessionStrategy->expects($this->once())->method('onAuthentication');
        $dependencies->requestStack->expects($this->once())->method('getCurrentRequest')->willReturn($request);
        $manager = $this->createInstance($dependencies, []);

        $manager->login($user);
    }

    public function testUpdatePassword()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies, []);

        $user = new UserMock();
        $user->setCustomerId('123');
        $user->setEmail('test@test.com');
        $user->setPlainPassword('somepass');

        $manager->update($user);

        $this->assertNull($user->getPlainPassword());
    }
}

class CustomerIdUserIdentifierProvider implements UserIdentifierProviderInterface
{
    public function getUserIdentifier(\Symfony\Component\Security\Core\User\UserInterface $user): string
    {
        return $user->getCustomerId().'.'.$user->getEmail();
    }

    public function getUserIdentifierByPropertyValues(array $values): string
    {
        return $values['customerId'].'.'.$values['email'];
    }

    public function getUserIdentifierProperties(): array
    {
        return ['customerId', 'email'];
    }
}

class UserManagerTestDependencies
{
    /** @var UserCheckerInterface|MockObject */
    public $userChecker;

    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var MailerManager|MockObject */
    public $mailerManager;

    /** @var UserRepository|MockObject */
    public $userRepository;

    /** @var TokenGeneratorInterface|MockObject */
    public $tokenGenerator;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var EncoderFactoryInterface|MockObject */
    public $encoderFactory;

    /** @var RouterInterface|MockObject */
    public $router;

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;

    /** @var FormInterface|MockObject */
    public $form;

    /** @var Message|MockObject */
    public $message;

    /** @var UserPasswordHasherInterface|MockObject */
    public $userPasswordHasher;

    /** @var TokenStorageInterface|MockObject */
    public $tokenStorage;

    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var SessionAuthenticationStrategyInterface|MockObject */
    public $sessionStrategy;

    /** @var string */
    public $defaultFirewall;

    /** @var UserIdentifierProviderResolver|MockObject */
    public $resolver;
}
