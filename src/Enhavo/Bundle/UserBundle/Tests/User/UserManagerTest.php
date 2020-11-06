<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Enhavo\Bundle\UserBundle\Tests\User;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\AppBundle\Mailer\MailerManager;
use Enhavo\Bundle\AppBundle\Mailer\Message;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Mapper\UserMapper;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserManagerTest extends TestCase
{
    private function createInstance(UserManagerTestDependencies $dependencies, $config, array $mapping = null)
    {
        if (!$mapping) {
            $mapping = [
                UserMapper::REGISTER_PROPERTIES => [
                    'customerId',
                    'email'
                ],
                UserMapper::CREDENTIAL_PROPERTIES => [
                    'customerId',
                    'email'
                ],
                'glue' => '.',
            ];
        }

        return new UserManager(
            $dependencies->entityManager,
            $dependencies->mailerManager,
            $dependencies->userRepository,
            $dependencies->getUserMapper($mapping),
            $dependencies->tokenGenerator,
            $dependencies->translator,
            $dependencies->formFactory,
            $dependencies->encoderFactory,
            $dependencies->router,
            $dependencies->eventDispatcher,
            $dependencies->tokenStorage,
            $dependencies->requestStack,
            $dependencies->sessionStrategy,
            $dependencies->userChecker,
            $dependencies->rememberMeService,
            $config
        );
    }

    private function createDependencies(): UserManagerTestDependencies
    {
        $dependencies = new UserManagerTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->mailerManager = $this->getMockBuilder(MailerManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            return $message .'.translated';
        });
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->formFactory->method('create')->willReturnCallback(function ($type) use ($dependencies) {
            return $dependencies->form;
        });
        $dependencies->encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)->getMock();
        $dependencies->encoder = $this->getMockBuilder(PasswordEncoderInterface::class)->getMock();
        $dependencies->encoder->method('encodePassword')->willReturnCallback(function ($password, $salt) {
            $this->assertEquals('password', $password);
            $this->assertNotNull($salt);

            return $password .'.hashed';
        });
        $dependencies->encoderFactory->method('getEncoder')->willReturn($dependencies->encoder);
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->message = $this->getMockBuilder(Message::class)->getMock();

        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $dependencies->sessionStrategy = $this->getMockBuilder(SessionAuthenticationStrategyInterface::class)->getMock();
        $dependencies->userChecker = $this->getMockBuilder(UserCheckerInterface::class)->getMock();

        return $dependencies;
    }

    public function testRegister()
    {
        $user = new UserMock();
        $user->setCustomerId('1337');
        $user->setEmail('user@enhavo.com');
        $user->setPlainPassword('password');

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) use ($user) {
            /** @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::TYPE_CREATED, $event->getType());
            $this->assertEquals($user, $event->getUser());
        });
        $dependencies->tokenGenerator->expects($this->once())->method('generateToken')->willReturn('__TOKEN__');
        $dependencies->mailerManager->expects($this->once())->method('createMessage')->willReturn($dependencies->message);
        $dependencies->mailerManager->expects($this->once())->method('sendMessage');
        $dependencies->message->method('setSubject')->willReturnCallback(function ($subject) {
            $this->assertEquals('mail.subject.translated', $subject);
        });
        $dependencies->message->method('setTemplate')->willReturnCallback(function ($subject) {
            $this->assertEquals('mail.html.twig', $subject);
        });
        $dependencies->message->method('setTo')->willReturnCallback(function ($subject) {
            $this->assertEquals('user@enhavo.com', $subject);
        });
        $dependencies->message->method('setFrom')->willReturnCallback(function ($subject) {
            $this->assertEquals('admin@enhavo.com', $subject);
        });
        $dependencies->message->method('setSenderName')->willReturnCallback(function ($subject) {
            $this->assertEquals('Enhavo', $subject);
        });
        $dependencies->message->method('setContentType')->willReturnCallback(function ($subject) {
            $this->assertEquals('content/type', $subject);
        });

        $manager = $this->createInstance($dependencies, [
            'theme' => [
                'register' => [
                    'mail_template' => 'mail.html.twig',
                    'mail_subject' => 'mail.subject',
                    'confirmation_route' => 'confirmation.route',
                    'translation_domain' => 'EnhavoUserBundle',
                    'mail_from' => 'admin@enhavo.com',
                    'sender_name' => 'Enhavo',
                    'content_type' => 'content/type'
                ]
            ]
        ]);

        $manager->register($user, 'theme', 'register');

        $this->assertEquals('1337.user@enhavo.com', $user->getUsername());
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

        $dependencies = $this->createDependencies();
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) use ($user) {
            /** @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::TYPE_REGISTRATION_CONFIRMED, $event->getType());
            $this->assertEquals($user, $event->getUser());
        });
        $dependencies->mailerManager->expects($this->once())->method('createMessage')->willReturn($dependencies->message);
        $dependencies->mailerManager->expects($this->once())->method('sendMessage');
        $dependencies->message->method('setSubject')->willReturnCallback(function ($subject) {
            $this->assertEquals('mail.subject.translated', $subject);
        });
        $dependencies->message->method('setTemplate')->willReturnCallback(function ($subject) {
            $this->assertEquals('mail.html.twig', $subject);
        });
        $dependencies->message->method('setTo')->willReturnCallback(function ($subject) {
            $this->assertEquals('user@enhavo.com', $subject);
        });
        $dependencies->message->method('setFrom')->willReturnCallback(function ($subject) {
            $this->assertEquals('admin@enhavo.com', $subject);
        });
        $dependencies->message->method('setSenderName')->willReturnCallback(function ($subject) {
            $this->assertEquals('Enhavo', $subject);
        });
        $dependencies->message->method('setContentType')->willReturnCallback(function ($subject) {
            $this->assertEquals('content/type', $subject);
        });

        $manager = $this->createInstance($dependencies, [
            'theme' => [
                'confirm' => [
                    'mail_template' => 'mail.html.twig',
                    'mail_subject' => 'mail.subject',
                    'confirmation_route' => 'confirmation.route',
                    'translation_domain' => 'EnhavoUserBundle',
                    'mail_from' => 'admin@enhavo.com',
                    'sender_name' => 'Enhavo',
                    'content_type' => 'content/type'
                ]
            ]
        ]);

        $manager->confirm($user, 'theme', 'confirm');

        $this->assertEquals('1337.user@enhavo.com', $user->getUsername());
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
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) use ($user) {
            /** @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::TYPE_PASSWORD_RESET_REQUESTED, $event->getType());
            $this->assertEquals($user, $event->getUser());
        });
        $dependencies->tokenGenerator->expects($this->once())->method('generateToken')->willReturn('__TOKEN__');
        $dependencies->mailerManager->expects($this->once())->method('createMessage')->willReturn($dependencies->message);
        $dependencies->mailerManager->expects($this->once())->method('sendMessage');
        $dependencies->message->method('setSubject')->willReturnCallback(function ($subject) {
            $this->assertEquals('mail.subject.translated', $subject);
        });
        $dependencies->message->method('setTemplate')->willReturnCallback(function ($subject) {
            $this->assertEquals('mail.html.twig', $subject);
        });
        $dependencies->message->method('setTo')->willReturnCallback(function ($subject) {
            $this->assertEquals('user@enhavo.com', $subject);
        });
        $dependencies->message->method('setFrom')->willReturnCallback(function ($subject) {
            $this->assertEquals('admin@enhavo.com', $subject);
        });
        $dependencies->message->method('setSenderName')->willReturnCallback(function ($subject) {
            $this->assertEquals('Enhavo', $subject);
        });
        $dependencies->message->method('setContentType')->willReturnCallback(function ($subject) {
            $this->assertEquals('content/type', $subject);
        });

        $manager = $this->createInstance($dependencies, [
            'theme' => [
                'reset_password' => [
                    'mail_template' => 'mail.html.twig',
                    'mail_subject' => 'mail.subject',
                    'confirmation_route' => 'confirmation.route',
                    'translation_domain' => 'EnhavoUserBundle',
                    'mail_from' => 'admin@enhavo.com',
                    'sender_name' => 'Enhavo',
                    'content_type' => 'content/type'
                ]
            ]
        ]);

        $manager->resetPassword($user, 'theme', 'reset_password');

        $this->assertEquals('1337.user@enhavo.com', $user->getUsername());
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
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) use ($user) {
            /** @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::TYPE_PASSWORD_CHANGED, $event->getType());
            $this->assertEquals($user, $event->getUser());
        });

        $manager = $this->createInstance($dependencies, []);

        $manager->changePassword($user);
        $this->assertEquals('password.hashed', $user->getPassword());
        $this->assertNull($user->getConfirmationToken());
    }

    public function testCreateForm()
    {
        $dependencies = $this->createDependencies();

        $manager = $this->createInstance($dependencies, [
            'admin' => [
                'create' => [
                    'form' => [
                        'class' => 'form.class',
                        'options' => ['option'=>'value'],
                    ],
                ],
            ],
        ], []);
        $user = new UserMock();

        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->formFactory->method('create')->willReturnCallback(function ($type, $resource, $options) use ($dependencies, $user) {
            $this->assertEquals('form.class', $type);
            $this->assertEquals($user, $resource);
            $this->assertEquals(['option1'=>'value1', 'option2'=>'value2'], $options);

            return $dependencies->form;
        });

        $form = $manager->createForm('admin', 'create', $user, ['option2' => 'value2']);

        $this->assertEquals($dependencies->form, $form);
    }

    public function testConfigShortcuts()
    {
        $dependencies = $this->createDependencies();

        $manager = $this->createInstance($dependencies, [
            'section1' => [
                'action1' => [
                    'template' => 'tpl1',
                ],
            ],
            'section2' => [
                'action2' => [
                    'redirect_route' => 'route1',
                ],
            ],
            'section3' => [
                'action3' => [
                    'javascripts' => ['path1'],
                ],
            ],
            'section4' => [
                'action4' => [
                    'stylesheets' => ['path2'],
                ],
            ],
        ], []);

        $this->assertEquals('tpl1', $manager->getTemplate('section1', 'action1'));
        $this->assertEquals('route1', $manager->getRedirectRoute('section2', 'action2'));
        $this->assertEquals(['path1'], $manager->getJavascripts('section3', 'action3'));
        $this->assertEquals(['path2'], $manager->getStylesheets('section4', 'action4'));
    }

    public function testConfigExceptionLevel0()
    {
        $dependencies = $this->createDependencies();

        $manager = $this->createInstance($dependencies, [
            'section1' => [
                'action1' => [
                    'item1' => 'value1',
                ],
            ],
        ], []);

        $this->assertEquals('_fallback', $manager->getConfig('section2', 'action1', 'item1', '_fallback'));
        $this->expectException(OptionDefinitionException::class);
        $manager->getConfig('section2', 'action1', 'item1');
    }

    public function testConfigExceptionLevel1()
    {
        $dependencies = $this->createDependencies();

        $manager = $this->createInstance($dependencies, [
            'section1' => [
                'action1' => [
                    'item1' => 'value1',
                ],
            ],
        ], []);

        $this->assertEquals('_fallback', $manager->getConfig('section1', 'action2', 'item1', '_fallback'));
        $this->expectException(OptionDefinitionException::class);
        $manager->getConfig('section1', 'action2', 'item1');
    }

    public function testConfigExceptionLevel2()
    {
        $dependencies = $this->createDependencies();

        $manager = $this->createInstance($dependencies, [
            'section1' => [
                'action1' => [
                    'item1' => 'value1',
                ],
            ],
        ], []);

        $this->assertEquals('_fallback', $manager->getConfig('section1', 'action1', 'item2', '_fallback'));
        $this->expectException(OptionDefinitionException::class);
        $manager->getConfig('section1', 'action1', 'item2');
    }

    public function testConfigLevel0()
    {
        $dependencies = $this->createDependencies();

        $manager = $this->createInstance($dependencies, [
            'section1' => [
                'action1' => [
                    'item1' => 'value1',
                ],
            ],
        ], []);

        $this->assertEquals([
            'action1' => [
                'item1' => 'value1',
            ],
        ], $manager->getConfig('section1'));
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
        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) use ($user) {
            /** @var UserEvent $event */
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::TYPE_ACTIVATED, $event->getType());
            $this->assertEquals($user, $event->getUser());
        });
        $dependencies->entityManager->expects($this->never())->method('persist');
        $dependencies->entityManager->expects($this->once())->method('flush');
        $dependencies->encoderFactory->expects($this->never())->method('getEncoder');

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

        $request = new Request();

        $dependencies = $this->createDependencies();
        $dependencies->tokenStorage->expects($this->exactly(2))->method('setToken')->willReturnCallback(function ($token) {
            $this->assertInstanceOf(UsernamePasswordToken::class, $token);
        });
        $dependencies->sessionStrategy->expects($this->exactly(2))->method('onAuthentication');
        $dependencies->requestStack->expects($this->exactly(2))->method('getCurrentRequest')->willReturn($request);
        $manager = $this->createInstance($dependencies, []);

        $manager->login('main', $user, null);

        $dependencies->rememberMeService = $this->getMockBuilder(RememberMeServicesInterface::class)->getMock();
        $dependencies->rememberMeService->expects($this->once())->method('loginSuccess')->willReturnCallback(function ($request, $response, $token) {
            $this->assertInstanceOf(Request::class, $request);
            $this->assertInstanceOf(Response::class, $response);
        });
        $manager = $this->createInstance($dependencies, []);

        $manager->login('main', $user, new Response());
    }


    public function testUpdatePassword()
    {
        $dependencies = $this->createDependencies();
        $dependencies->encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)->getMock();
        $encoder = new NativePasswordEncoder();
        $dependencies->encoderFactory->method('getEncoder')->willReturn($encoder);
        $manager = $this->createInstance($dependencies, []);

        $user = new UserMock();
        $user->setPlainPassword('nosalt');
        $user->setSalt('notnull');

        $manager->updatePassword($user);

        $this->assertNull($user->getPlainPassword());
        $this->assertNull($user->getSalt());
    }

    public function testMapValues()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies, []);

        $user = new UserMock();
        $values = [
            'customerId' => 1337,
            'email' => 'e@mail.com',
        ];

        $manager->mapValues($user, $values);

        $this->assertEquals(1337, $user->getCustomerId());
        $this->assertEquals('e@mail.com', $user->getEmail());

        $this->expectException(PropertyNotExistsException::class);

        $values = [
            'clusterId' => 'abcdefg',
            'email' => 'hijklmnop',
        ];

        $manager->mapValues($user, $values);
    }
}

class UserManagerTestDependencies
{
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

    /** @var FormFactoryInterface|MockObject */
    public $formFactory;

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

    /** @var PasswordEncoderInterface|MockObject */
    public $encoder;

    /** @var TokenStorageInterface|MockObject */
    public $tokenStorage;

    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var SessionAuthenticationStrategyInterface|MockObject */
    public $sessionStrategy;

    /** @var UserCheckerInterface|MockObject */
    public $userChecker;

    /** @var RememberMeServicesInterface|MockObject */
    public $rememberMeService;

    public function getUserMapper($config): UserMapper
    {
        return new UserMapper($config);
    }
}

