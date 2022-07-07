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
use Enhavo\Bundle\UserBundle\EventListener\UserAuthenticationSubscriber;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        $dependencies->session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();

        $dependencies->request->method('getSession')->willReturn($dependencies->session);
        $dependencies->request->attributes = new ParameterBag();
        $dependencies->request->request = new ParameterBag();
        $dependencies->request->attributes->set('_config', 'config');

        $dependencies->requestStack->method('getCurrentRequest')->willReturn($dependencies->request);
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
        ], $events);
    }

    /**
     * @throws Exception
     */
    public function testOnLogin()
    {
        $dependencies = $this->createDependencies();

        $user = new UserMock();
        $user->setEmail('user@enhavo.com');
        $event = new UserEvent(UserEvent::TYPE_LOGIN_SUCCESS, $user);

        $subscriber = $this->createInstance($dependencies);
        $dependencies->userManager->expects($this->once())->method('update')->willReturnCallback(function (UserInterface $user) {
            $this->assertNotNull($user->getLastLogin());
            $this->assertEquals(0, $user->getFailedLoginAttempts());
            $this->assertNull($user->getlastFailedLoginAttempt());
        });

        $subscriber->onUserEvent($event);


    }

    /**
     * @throws Exception
     */
    public function testOnLoginFailureBadCredentials()
    {
        $dependencies = $this->createDependencies();
        $dependencies->configurationProvider->expects($this->never())->method('getLoginConfiguration');
        $dependencies->session->expects($this->once())->method('get')->willReturnCallback(function ($key) {
            $this->assertEquals(Security::AUTHENTICATION_ERROR, $key);

            return new BadCredentialsException();
        });
        $dependencies->userManager->expects($this->once())->method('update');

        $user = new UserMock();
        $user->setFailedLoginAttempts(1);
        $event = new UserEvent(UserEvent::TYPE_LOGIN_FAILED, $user);
        $subscriber = $this->createInstance($dependencies);

        $subscriber->onUserEvent($event);

        $this->assertEquals(2, $user->getFailedLoginAttempts());
        $this->assertNotNull($user->getLastFailedLoginAttempt());
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

    /** @var Session|MockObject */
    public $session;

    /** @var TranslatorInterface|MockObject */
    public $translator;
}
