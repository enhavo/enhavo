<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserLoginEvent;
use Enhavo\Bundle\UserBundle\Mapper\UserMapper;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class FormLoginAuthenticatorTest extends TestCase
{
    private function createInstance(FormLoginAuthenticatorTestDependencies $dependencies, $mappings = null, $className = null): FormLoginAuthenticator
    {
        $className = $className ?? User::class;
        $mappings = $mappings ?? [
                UserMapper::CREDENTIAL_PROPERTIES => [
                    'customerId', 'email'
                ],
                UserMapper::REGISTER_PROPERTIES => [
                    'customerId', 'email'
                ],
                'glue' => '.',
        ];

        return new FormLoginAuthenticator(
            $dependencies->userManager,
            $dependencies->userRepository,
            $dependencies->configurationProvider,
            $dependencies->urlGenerator,
            $dependencies->tokenManager,
            $dependencies->passwordEncoder,
            $dependencies->getUserMapper($mappings),
            $dependencies->eventDispatcher,
            $className
        );
    }

    private function createDependencies(): FormLoginAuthenticatorTestDependencies
    {
        $dependencies = new FormLoginAuthenticatorTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->configurationProvider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        $dependencies->urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $dependencies->urlGenerator->method('generate')->willReturnCallback(function ($route) {
            return $route .'.generated';
        });
        $dependencies->tokenManager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMock();
        $dependencies->passwordEncoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag();
        $dependencies->request->request = new ParameterBag();
        $dependencies->request->attributes->set('_config', 'config');
        $dependencies->request->request->set('_password', '__PW__');
        $dependencies->request->request->set('_csrf_token', '__CSRF__');
        $dependencies->request->request->set('_email', 'user@enhavo.com');
        $dependencies->request->request->set('_customerId', '1337');
        $dependencies->session = $this->getMockBuilder(Session::class)->getMock();
        $dependencies->session->method('get')->willReturnCallback(function ($key) {
            return $key.'.session';
        });
        $dependencies->request->method('getSession')->willReturn($dependencies->session);

        return $dependencies;
    }

    public function testSupports()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->attributes->set('_config', 'config');
        $dependencies->request->method('isMethod')->willReturn(true);
        $dependencies->request->attributes->set('_route', 'config.login.route');

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function() {
           $configuration = new LoginConfiguration();
           $configuration->setRoute('config.login.route');
           return $configuration;
        });

        $instance = $this->createInstance($dependencies);
        $this->assertTrue($instance->supports($dependencies->request));

        $dependencies->request->attributes->set('_route', 'any.other.route');
        $instance = $this->createInstance($dependencies);
        $this->assertFalse($instance->supports($dependencies->request));
    }

    public function testSupportsGet()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->attributes->set('_config', 'config');
        $dependencies->request->method('isMethod')->willReturn(false);

        $dependencies->request->attributes->set('_route', 'config.login.route');
        $instance = $this->createInstance($dependencies);
        $this->assertFalse($instance->supports($dependencies->request));

        $dependencies->request->attributes->set('_route', 'any.other.route');
        $instance = $this->createInstance($dependencies);
        $this->assertFalse($instance->supports($dependencies->request));
    }

    public function testGetCredentialsAndUser()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenManager->expects($this->once())->method('isTokenValid')->willReturn(true);


        $instance = $this->createInstance($dependencies);
        $credentials = $instance->getCredentials($dependencies->request);
        /** @var UserProviderInterface|MockObject $userProvider */
        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $userProvider->expects($this->exactly(1))->method('loadUserByUsername')->willReturnCallback(function ($username) {
            $this->assertEquals('1337.user@enhavo.com', $username);

            $user = new User();
            $user->setUsername($username);
            return $user;
        });

        $this->assertEquals([
            'csrf_token'=>'__CSRF__',
            'customerId'=>'1337',
            'email'=>'user@enhavo.com',
            'password'=>'__PW__',
        ], $credentials);

        $user = $instance->getUser($credentials, $userProvider);
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    public function testGetUserInvalidToken()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenManager->expects($this->once())->method('isTokenValid')->willReturn(false);

        $instance = $this->createInstance($dependencies);
        $credentials = $instance->getCredentials($dependencies->request);

        $this->expectException(InvalidCsrfTokenException::class);
        $instance->getUser($credentials, $this->getMockBuilder(UserProviderInterface::class)->getMock());
    }

    public function testGetUserNotFound()
    {
        $dependencies = $this->createDependencies();
        $dependencies->tokenManager->expects($this->once())->method('isTokenValid')->willReturn(true);

        $instance = $this->createInstance($dependencies);
        $credentials = $instance->getCredentials($dependencies->request);

        /** @var UserProviderInterface|MockObject $userProvider */
        $userProvider = $this->getMockBuilder(UserProviderInterface::class)->getMock();
        $userProvider->expects($this->exactly(1))->method('loadUserByUsername')->willReturn(null);

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $instance->getUser($credentials, $userProvider);
    }

    public function testAuthenticationSuccess()
    {
        $user = new UserMock();
        $dependencies = $this->createDependencies();

        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event) use ($user) {
            $this->assertInstanceOf(UserLoginEvent::class, $event);
            $this->assertEquals($user, $event->getUser());
            return $event;
        });

        $instance = $this->createInstance($dependencies);
        /** @var TokenInterface|MockObject $token */
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $response = $instance->onAuthenticationSuccess($dependencies->request, $token, 'user');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('_security.user.target_path.session', $response->getTargetUrl());
    }

    public function testAuthenticationFailure()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function() {
            $configuration = new LoginConfiguration();
            $configuration->setRoute('config.login.route');
            return $configuration;
        });
        $dependencies->request->expects($this->once())->method('get')->willReturn('failed@enhavo.com');

        $instance = $this->createInstance($dependencies);

        $response = $instance->onAuthenticationFailure($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('config.login.route.generated', $response->getTargetUrl());
    }

    public function testStart()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function() {
            $configuration = new LoginConfiguration();
            $configuration->setRoute('config.login.route');
            return $configuration;
        });

        $instance = $this->createInstance($dependencies);

        $response = $instance->start($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('config.login.route.generated', $response->getTargetUrl());
    }

    public function testGetPassword()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $this->assertEquals('__PW__', $instance->getPassword([
            'password' => '__PW__',
            'bassword' => '__BW__'
        ]));
    }

    public function testCheckCredentials()
    {
        $dependencies = $this->createDependencies();
        $dependencies->passwordEncoder->expects($this->once())->method('isPasswordValid')->willReturnCallback(function ($user, $pw) {
            $this->assertEquals('__PW__', $pw);

            return true;
        });

        $instance = $this->createInstance($dependencies);

        $user = new UserMock();
        $this->assertTrue($instance->checkCredentials([
            'password' => '__PW__',
        ], $user));
    }
}

class FormLoginAuthenticatorTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var ConfigurationProvider|MockObject */
    public $configurationProvider;

    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var UrlGeneratorInterface|MockObject */
    public $urlGenerator;

    /** @var CsrfTokenManagerInterface|MockObject */
    public $tokenManager;

    /** @var UserPasswordEncoderInterface|MockObject */
    public $passwordEncoder;

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;

    /** @var Request|MockObject */
    public $request;

    /** @var Session */
    public $session;

    /** @var UserRepository|MockObject */
    public $userRepository;

    public function getUserMapper($mappings)
    {
        return new UserMapper($mappings);
    }
}
