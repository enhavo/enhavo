<?php
/**
 * @author blutze-media
 * @since 2020-11-04
 */

namespace Enhavo\Bundle\UserBundle\Tests\Security\Authentication;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Model\Credentials;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserMapper;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class FormLoginAuthenticatorTest extends TestCase
{
    private function createInstance(FormLoginAuthenticatorTestDependencies $dependencies, $className = null): FormLoginAuthenticator
    {
        $className = $className ?? User::class;

        return new FormLoginAuthenticator(
            $dependencies->userManager,
            $dependencies->userRepository,
            $dependencies->configurationProvider,
            $dependencies->urlGenerator,
            $dependencies->eventDispatcher,
            $dependencies->formFactory,
            $className,
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
        $dependencies->eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag();
        $dependencies->request->request = new ParameterBag();
        $dependencies->request->attributes->set('_config', 'config');
        $dependencies->session = $this->getMockBuilder(Session::class)->getMock();
        $dependencies->session->method('get')->willReturnCallback(function ($key) {
            return $key.'.session';
        });
        $dependencies->request->method('getSession')->willReturn($dependencies->session);
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->form = $this->getMockBuilder(Form::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function testSupports()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->method('isMethod')->willReturnCallback(function($method) {
            return $method === 'POST';
        });
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
        $dependencies->request->method('isMethod')->willReturn(function($method) {
            return $method === 'GET';
        });

        $dependencies->request->attributes->set('_route', 'config.login.route');
        $instance = $this->createInstance($dependencies);
        $this->assertFalse($instance->supports($dependencies->request));

        $dependencies->request->attributes->set('_route', 'any.other.route');
        $instance = $this->createInstance($dependencies);
        $this->assertFalse($instance->supports($dependencies->request));
    }

    public function testBadgeData()
    {
        $dependencies = $this->createDependencies();

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function() {
            $configuration = new LoginConfiguration();
            $configuration->setFormClass('formClass');
            $configuration->setFormOptions([]);
            return $configuration;
        });

        $dependencies->formFactory->method('create')->willReturn($dependencies->form);

        $credentials = new Credentials();
        $credentials->setUserIdentifier('1337.user@enhavo.com');
        $credentials->setPassword('__PW__');
        $credentials->setCsrfToken('__CSRF__');
        $dependencies->form->method('getData')->willReturn($credentials);

        $instance = $this->createInstance($dependencies);
        $passport = $instance->authenticate($dependencies->request);

        /** @var UserBadge $userBadge */
        $userBadge = $passport->getBadge(UserBadge::class);
        $this->assertEquals('1337.user@enhavo.com', $userBadge->getUserIdentifier());

        /** @var PasswordCredentials $passwordCredentials */
        $passwordCredentials = $passport->getBadge(PasswordCredentials::class);
        $this->assertEquals('__PW__', $passwordCredentials->getPassword());

        /** @var CsrfTokenBadge $csrfTokenBadge */
        $csrfTokenBadge = $passport->getBadge(CsrfTokenBadge::class);
        $this->assertEquals('__CSRF__', $csrfTokenBadge->getCsrfToken());
        $this->assertEquals('authenticate', $csrfTokenBadge->getCsrfTokenId());
    }

    public function testAuthenticationSuccess()
    {
        $user = new UserMock();
        $dependencies = $this->createDependencies();

        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) use ($user) {
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals($user, $event->getUser());
            $this->assertEquals(UserEvent::LOGIN_SUCCESS, $name);
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

        $dependencies->userRepository->method('loadUserByIdentifier')->willReturnCallback(function($name) {
            if ($name === '1337.user@enhavo.com') {
                $user = new UserMock();
                $user->setUsername($name);
                return $user;
            }
            return null;
        });

        $dependencies->eventDispatcher->expects($this->once())->method('dispatch')->willReturnCallback(function ($event, $name) {
            $this->assertInstanceOf(UserEvent::class, $event);
            $this->assertEquals(UserEvent::LOGIN_FAILURE, $name);
            $this->assertEquals('1337.user@enhavo.com', $event->getUser()->getUsername());
            return $event;
        });

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function($name) {
            $loginConfiguration = new LoginConfiguration();
            $loginConfiguration->setRoute('login_route');
            $loginConfiguration->setFormClass('formClass');
            $loginConfiguration->setFormOptions([]);
            return $loginConfiguration;
        });


        $dependencies->formFactory->method('create')->willReturn($dependencies->form);

        $credentials = new Credentials();
        $credentials->setUserIdentifier('1337.user@enhavo.com');
        $dependencies->form->method('getData')->willReturn($credentials);

        $instance = $this->createInstance($dependencies);

        $response = $instance->onAuthenticationFailure($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('login_route.generated', $response->getTargetUrl());
    }

    public function testAuthenticationFailureWithFailurePath()
    {
        $dependencies = $this->createDependencies();

        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function($name) {
            $loginConfiguration = new LoginConfiguration();
            $loginConfiguration->setFormClass('formClass');
            $loginConfiguration->setFormOptions([]);
            return $loginConfiguration;
        });

        $dependencies->formFactory->method('create')->willReturn($dependencies->form);

        $dependencies->form->method('getData')->willReturn(new Credentials());

        $dependencies->request->method('get')->willReturnCallback(function($key) {
            if ($key === '_failure_path') {
                return '/login/failure';
            }
            return null;
        });

        $instance = $this->createInstance($dependencies);
        $response = $instance->onAuthenticationFailure($dependencies->request, new AuthenticationException());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/login/failure', $response->getTargetUrl());
    }
}

class FormLoginAuthenticatorTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var ConfigurationProvider|MockObject */
    public $configurationProvider;

    /** @var UrlGeneratorInterface|MockObject */
    public $urlGenerator;

    /** @var EventDispatcherInterface|MockObject */
    public $eventDispatcher;

    /** @var Request|MockObject */
    public $request;

    /** @var Session */
    public $session;

    /** @var UserRepository|MockObject */
    public $userRepository;

    /** @var FormFactoryInterface|MockObject */
    public $formFactory;

    /** @var Form|MockObject */
    public $form;
}
