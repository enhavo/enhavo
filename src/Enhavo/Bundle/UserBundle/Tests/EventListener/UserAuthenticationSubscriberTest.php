<?php
/**
 * @author blutze-media
 * @since 2020-11-05
 */

namespace Enhavo\Bundle\UserBundle\Tests\EventListener;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ResetPassword\ResetPasswordRequestConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Event\UserLoginEvent;
use Enhavo\Bundle\UserBundle\Event\UserLoginFailureEvent;
use Enhavo\Bundle\UserBundle\EventListener\UserAuthenticationSubscriber;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function Clue\StreamFilter\fun;

class UserAuthenticationSubscriberTest extends TestCase
{
    private function createDependencies(): UserAuthenticationSubscriberTestDependencies
    {
        $dependencies = new UserAuthenticationSubscriberTestDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($id, $opts, $domain) {
            $this->assertEquals('EnhavoUserBundle', $domain);
            return sprintf('%s.%s', $id, $domain);
        });
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->configurationProvider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->method('getSession')->willReturnCallback(function () {
            $session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
            $session->method('getFlashBag')->willReturn(new FlashBag());
            return $session;
        });
        $dependencies->request->attributes = new ParameterBag();
        $dependencies->request->request = new ParameterBag();
        $dependencies->request->attributes->set('_config', 'config');

        $dependencies->requestStack->method('getMainRequest')->willReturn($dependencies->request);
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();

        return $dependencies;
    }

    private function createInstance(UserAuthenticationSubscriberTestDependencies $dependencies): UserAuthenticationSubscriber
    {
        return new UserAuthenticationSubscriber(
            $dependencies->userManager,
            $dependencies->configurationProvider,
            $dependencies->requestStack,
            $dependencies->router,
            $dependencies->translator,
        );
    }

    public function testGetSubscribedEvents()
    {
        $events = UserAuthenticationSubscriber::getSubscribedEvents();

        $this->assertEquals([
            UserEvent::class => 'onUserEvent',
            UserLoginEvent::class => 'onLogin',
            UserLoginFailureEvent::class => 'onLoginFailure',
        ], $events);
    }

    /**
     * @throws \Exception
     */
    public function testOnLogin()
    {
        $dependencies = $this->createDependencies();

        $user = new UserMock();
        $user->setEmail('user@enhavo.com');
        $event = new UserLoginEvent($user);

        $subscriber = $this->createInstance($dependencies);
        $dependencies->userManager->expects($this->once())->method('update')->willReturnCallback(function (UserInterface $user) {
            $this->assertNotNull($user->getLastLogin());
            $this->assertEquals(0, $user->getFailedLoginAttempts());
            $this->assertNull($user->getlastFailedLoginAttempt());
        });

        $subscriber->onLogin($event);

        $user->setPlainPassword('__PW__');
        $user->setPasswordUpdatedAt((new \DateTime())->modify('-2 minute'));
        $dependencies->configurationProvider->expects($this->once())->method('getLoginConfiguration')->willReturnCallback(function ($configKey) {
            $config = new LoginConfiguration();
            $config->setPasswordMaxAge('1 minute');

            return $config;
        });
        $dependencies->configurationProvider->expects($this->once())->method('getResetPasswordRequestConfiguration')->willReturnCallback(function ($configKey) {
            $config = new ResetPasswordRequestConfiguration();
            $config->setRedirectRoute('config.redirect.route');

            return $config;
        });

        $dependencies->userManager->expects($this->once())->method('logout');
        $dependencies->userManager->expects($this->once())->method('resetPassword')->willReturnCallback(function (UserInterface $user) {
            $this->assertNotEquals('__PW__', $user->getPlainPassword());
            $this->assertNotNull($user->getLastLogin());
            $this->assertEquals(0, $user->getFailedLoginAttempts());
            $this->assertNull($user->getlastFailedLoginAttempt());
        });
        $dependencies->router->expects($this->once())->method('generate')->willReturnCallback(function ($route) {
            return $route . '.routed';
        });

        $subscriber->onLogin($event);

        $this->assertEquals('config.redirect.route.routed', $event->getResponse()->getTargetUrl());
    }

    public function testOnLoginFailure()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurationProvider->method('getLoginConfiguration')->willReturnCallback(function () {
            $configuration = new LoginConfiguration();
            $configuration->setMaxLoginAttempts(2);
            return $configuration;
        });
        $dependencies->configurationProvider->method('getResetPasswordRequestConfiguration')->willReturnCallback(function() {
            $configuration = new ResetPasswordRequestConfiguration();
            $configuration->setRedirectRoute('config.redirect.route');
            return $configuration;
        });
        $dependencies->router->expects($this->once())->method('generate')->willReturnCallback(function ($route) {
            return $route . '.routed';
        });
        $user = new UserMock();
        $user->setFailedLoginAttempts(1);
        $user->setPlainPassword('__OLD__');
        $user->setEmail('user@enhavo.com');
        $event = new UserLoginFailureEvent($user);

        $subscriber = $this->createInstance($dependencies);

        $dependencies->userManager->expects($this->once())->method('update');
        $dependencies->userManager->expects($this->once())->method('resetPassword');
        $subscriber->onLoginFailure($event);

        $this->assertNull($event->getResponse());
        $this->assertEquals(2, $user->getFailedLoginAttempts());

        $subscriber->onLoginFailure($event);

        $this->assertNotEquals('__OLD__', $user->getPlainPassword());
        $this->assertEquals(0, $user->getFailedLoginAttempts());
        $this->assertNull($user->getLastFailedLoginAttempt());
        $this->assertEquals('config.redirect.route.routed', $event->getResponse()->getTargetUrl());
    }
}

class UserAuthenticationSubscriberTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var ConfigurationProvider|MockObject */
    public $configurationProvider;

    /** @var RequestStack|MockObject */
    public $requestStack;

    /** @var RouterInterface|MockObject */
    public $router;

    /** @var Request|MockObject */
    public $request;

    /** @var TranslatorInterface|MockObject */
    public $translator;
}
