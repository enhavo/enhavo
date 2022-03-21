<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Configuration\ChangeEmail\ChangeEmailRequestConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Controller\ChangeEmailController;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangeEmailControllerTest //extends TestCase
{
    private function createInstance(ChangeEmailControllerTestDependencies $dependencies)
    {
        return new ChangeEmailControllerMock(
            $dependencies->userManager,
            $dependencies->provider,
            $dependencies->userRepository,
            $dependencies->userFactory,
            $dependencies->translator,
            $dependencies->errorResolver
        );
    }

    private function createDependencies(): ChangeEmailControllerTestDependencies
    {
        $dependencies = new ChangeEmailControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->configurationProvider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateManager = $this->getMockBuilder(TemplateManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateManager->method('getTemplate')->willReturnCallback(function ($template) {
            return $template .'.managed';
        });
        $dependencies->userFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message, $b, $c, $d) {
            return $message .'.translated';
        });
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag([]);

        $dependencies->errorResolver = $this->getMockBuilder(FormErrorResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->errorResolver->method('getErrorFieldNames')->willReturn([]);
        $dependencies->errorResolver->method('getErrorMessages')->willReturn([]);

        $dependencies->userManager->method('createForm')->willReturn(
            $dependencies->form
        );
        $dependencies->userManager->method('changeEmail')->willReturnCallback(function ($user, $email) use ($dependencies) {
            return $dependencies->mailSent;
        });

        $dependencies->form->method('isSubmitted')->willReturnCallback(function () use ($dependencies) {
            return $dependencies->isSubmitted;
        });
        $dependencies->form->method('isValid')->willReturnCallback(function () use ($dependencies) {
            return $dependencies->isValid;
        });
        $dependencies->form->method('get')->willReturnCallback(function ($key) {
            $field = $this->getMockBuilder(FormInterface::class)->getMock();
            $field->method('getData')->willReturn($key .'.data');

            return $field;
        });

        return $dependencies;
    }

    public function testRequest()
    {
        $dependencies = $this->createDependencies();

        $dependencies->provider->method('getChangeMailRequestConfiguration')->willReturnCallback(function() {
            $configuration = new ChangeEmailRequestConfiguration();
            $configuration->setRoute('config.login.route');
            $configuration->set('config.login.route');
            return $configuration;
        });

        $dependencies->userFactory->method('createNew')->willReturn(new User());
        $dependencies->userManager->method('getTemplate')->willReturn('request.html.twig');
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');

        $controller = $this->createInstance($dependencies);
        $dependencies->request->attributes->set('_config', 'theme');

        // unsubmitted
        $dependencies->isSubmitted = false;
        $dependencies->isValid = false;
        $response = $controller->requestAction($dependencies->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('request.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        // submitted with errors
        $dependencies->isSubmitted = true;
        $dependencies->isValid = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('request.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());


        // submitted no errors
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('redirect.route.generated', $response->getTargetUrl());


        // submitted not sent
        $dependencies->mailSent = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('request.html.twig.managed.rendered', $response->getContent());
    }

    public function testRequestAjax()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->method('isXmlHttpRequest')->willReturn(true);
        $dependencies->userFactory->method('createNew')->willReturn(new User());
        $dependencies->userManager->method('getTemplate')->willReturn('request.html.twig');
        $dependencies->userManager->method('getStylesheets')->willReturn([]);
        $dependencies->userManager->method('getJavascripts')->willReturn([]);
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');

        $controller = $this->createInstance($dependencies);
        $dependencies->request->attributes->set('_config', 'theme');

        // unsubmitted
        $dependencies->isSubmitted = false;
        $dependencies->isValid = false;
        $response = $controller->requestAction($dependencies->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('request.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        // submitted with errors
        $dependencies->isSubmitted = true;
        $dependencies->isValid = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":true,"errors":{"fields":[],"messages":[]}}', $response->getContent());

        // submitted no errors
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":false,"errors":[],"message":"change_email.flash.message.success.translated","redirect_url":"redirect.route.generated"}', $response->getContent());

        // submitted not sent
        $dependencies->mailSent = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":true,"errors":{"fields":[],"messages":[]},"message":"change_email.flash.message.error.translated"}', $response->getContent());
    }

    public function testCheck()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('getTemplate')->willReturnCallback(function ($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('change_email_check', $action);

            return 'check.html.twig';
        });
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->attributes->set('_config', 'theme');

        $response = $controller->checkAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('check.html.twig.managed.rendered', $response->getContent());
    }
}

class ChangeEmailControllerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var ConfigurationProvider|MockObject */
    public $provider;

    /** @var UserRepository|MockObject */
    public $userRepository;

    /** @var TemplateManager|MockObject */
    public $templateManager;

    /** @var FactoryInterface|MockObject */
    public $userFactory;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var FormInterface|MockObject */
    public $form;

    /** @var Request|MockObject */
    public $request;

    /** @var FormErrorResolver|MockObject */
    public $errorResolver;

    public $isSubmitted = false;

    public $isValid = false;

    public $userExists = true;

    public $mailSent = true;
}

class ChangeEmailControllerMock extends ChangeEmailController
{
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return new Response($view .'.rendered');
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $route .'.generated';
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

    protected function getUser()
    {
        return new UserMock();
    }
}
