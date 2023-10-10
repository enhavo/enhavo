<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;


use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\UserBundle\Controller\SecurityController;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityControllerTest //extends TestCase
{
    private function createInstance(SecurityControllerTestDependencies $dependencies)
    {
        return new SecurityControllerMock(
            $dependencies->userManager,
            $dependencies->templateResolver,
            $dependencies->tokenManager,
            $dependencies->translator
        );
    }

    private function createDependencies(): SecurityControllerTestDependencies
    {
        $dependencies = new SecurityControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userManager->method('getConfigKey')->willReturnCallback(function ($request) {
            return $request->attributes->get('_config');
        });
        $dependencies->templateResolver = $this->getMockBuilder(TemplateResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->tokenManager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($id, $params, $domain) {
            return $id.'.translated';
        });

        return $dependencies;
    }

    public function testLoginAction()
    {
        $dependencies = $this->createDependencies();
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->method('getSession')->willReturn(new ParameterBag());
        $request->attributes = new ParameterBag();
        $request->query = new ParameterBag();
        $request->attributes->set('_config', 'theme');

        /** @var AuthenticationUtils|MockObject $authenticationUtils */
        $authenticationUtils = $this->getMockBuilder(AuthenticationUtils::class)->disableOriginalConstructor()->getMock();
        $authenticationUtils->method('getLastAuthenticationError')->willReturn('error');
        $authenticationUtils->method('getLastUsername')->willReturn('username@enhavo.com');
        $dependencies->tokenManager->method('getToken')->willReturn(new CsrfToken('_id_', '_value_'));
        $dependencies->userManager->method('resolve')->willReturnCallback(function ($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('login', $action);

            return 'login.html.twig';
        });
        $dependencies->userManager->method('getStylesheets')->willReturnCallback(function ($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('login', $action);

            return [];
        });
        $dependencies->userManager->method('getJavascripts')->willReturnCallback(function ($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('login', $action);

            return [];
        });
        $dependencies->templateResolver->method('resolve')->willReturnCallback(function($template) {
            return $template .'.managed';
        });

        $response = $controller->loginAction($request, $authenticationUtils);
        $this->assertEquals('login.html.twig.managed.rendered', $response->getContent());
    }

    public function testLoginActionGranted()
    {
        $dependencies = $this->createDependencies();
        $controller = $this->createInstance($dependencies);
        $controller->isGranted = true;

        /** @var Request|MockObject $request */
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->attributes = new ParameterBag();
        $request->attributes->set('_config', 'theme');

        /** @var AuthenticationUtils|MockObject $authenticationUtils */
        $authenticationUtils = $this->getMockBuilder(AuthenticationUtils::class)->disableOriginalConstructor()->getMock();

        $dependencies->userManager->method('getRedirectRoute')->willReturnCallback(function ($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('login', $action);

            return 'redirect.route';
        });

        $response = $controller->loginAction($request, $authenticationUtils);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('redirect.route.generated', $response->getTargetUrl());
    }

    public function testCheckAction()
    {
        $dependencies = $this->createDependencies();
        $controller = $this->createInstance($dependencies);
        $this->expectException(\LogicException::class);
        $controller->checkAction();
    }

    public function testLogoutAction()
    {
        $dependencies = $this->createDependencies();
        $controller = $this->createInstance($dependencies);
        $this->expectException(\LogicException::class);
        $controller->logoutAction();
    }
}

class SecurityControllerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var TemplateResolver|MockObject */
    public $templateResolver;

    /** @var CsrfTokenManagerInterface|MockObject */
    public $tokenManager;

    /** @var TranslatorInterface */
    public $translator;
}

class SecurityControllerMock extends SecurityController
{
    public $isGranted = false;

    protected function isGranted($role, $subject = null): bool
    {
        return $this->isGranted;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return new Response($view . '.rendered', 200);
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $route . '.generated';
    }


    public $flashMessages = [];
    protected function getFlashMessages()
    {
        return $this->flashMessages;
    }

    protected function addFlash(string $type, $message): void
    {
        $this->flashMessages[$type] = $message;
    }
}
