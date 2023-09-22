<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;


use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Controller\ResetPasswordController;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordControllerTest //extends TestCase
{
    private function createInstance(ResetPasswordControllerTestDependencies $dependencies)
    {
        return new ResetPasswordControllerMock(
            $dependencies->userManager,
            $dependencies->userRepository,
            $dependencies->templateResolver,
            $dependencies->translator,
            $dependencies->errorResolver
        );
    }

    private function createDependencies(): ResetPasswordControllerTestDependencies
    {
        $dependencies = new ResetPasswordControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateResolver = $this->getMockBuilder(TemplateResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateResolver->method('getTemplate')->willReturnCallback(function ($template) {
            return $template .'.managed';
        });
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
        $dependencies->userManager->method('getConfigKey')->willReturnCallback(function ($request) {
            return $request->attributes->get('_config');
        });

        $dependencies->form->method('isSubmitted')->willReturnCallback(function () use ($dependencies) {
            return $dependencies->isSubmitted;
        });
        $dependencies->form->method('isValid')->willReturnCallback(function () use ($dependencies) {
            return $dependencies->isValid;
        });

        return $dependencies;
    }

    public function testRequest()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('getTemplate')->willReturn('request.html.twig');
        $dependencies->userManager->method('getStylesheets')->willReturn([]);
        $dependencies->userManager->method('getJavascripts')->willReturn([]);
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');
        $dependencies->userRepository->expects($this->exactly(2))->method('loadUserByUsername')->willReturnCallback(function($username) use ($dependencies) {
            if ($dependencies->userExists) {
                return new User();
            }

            return null;
        });
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


        // submitted no errors user found
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('redirect.route.generated', $response->getTargetUrl());

        // submitted no errors no user
        $dependencies->userExists = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('request.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals('reset_password.flash.message.error.translated', $controller->flashMessages['error']);
    }

    public function testRequestAjax()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->method('isXmlHttpRequest')->willReturn(true);
        $dependencies->userManager->method('getTemplate')->willReturn('request.html.twig');
        $dependencies->userManager->method('getStylesheets')->willReturn([]);
        $dependencies->userManager->method('getJavascripts')->willReturn([]);
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');
        $dependencies->userRepository->expects($this->exactly(2))->method('loadUserByUsername')->willReturnCallback(function($username) use ($dependencies) {
            if ($dependencies->userExists) {
                return new User();
            }

            return null;
        });
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


        // submitted no errors user found
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":false,"errors":[],"message":"reset_password.flash.message.success.translated","redirect_url":"redirect.route.generated"}', $response->getContent());

        // submitted no errors no user
        $dependencies->userExists = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('{"error":true,"errors":{"fields":[],"messages":[]},"message":"reset_password.flash.message.error.translated"}', $response->getContent());
        $this->assertEquals('reset_password.flash.message.error.translated', $controller->flashMessages['error']);
    }

    public function testCheck()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('getTemplate')->willReturnCallback(function($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('reset_password_check', $action);

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

    public function testConfirm()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('getRedirectRoute')->willReturn('redirect.route');
        $dependencies->userRepository->method('findByConfirmationToken')->willReturn(new User());
        $dependencies->userManager->method('getTemplate')->willReturnCallback(function($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('reset_password_confirm', $action);

            return 'confirm.html.twig';
        });
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->attributes->set('_config', 'theme');

        $token = '__TOKEN__';

        // unsubmitted
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('confirm.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        // submitted invalid
        $dependencies->isSubmitted = true;
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('confirm.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());

        // submitted valid
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('redirect.route.generated', $response->getTargetUrl());
    }

    public function testConfirmAjax()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('getRedirectRoute')->willReturn('redirect.route');
        $dependencies->userRepository->method('findByConfirmationToken')->willReturn(new User());
        $dependencies->userManager->method('getTemplate')->willReturnCallback(function($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('reset_password_confirm', $action);

            return 'confirm.html.twig';
        });
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->method('isXmlHttpRequest')->willReturn(true);
        $request->attributes->set('_config', 'theme');

        $token = '__TOKEN__';

        // unsubmitted
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('confirm.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        // submitted invalid
        $dependencies->isSubmitted = true;
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":true,"errors":{"fields":[],"messages":[]}}', $response->getContent());

        // submitted valid
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":false,"errors":[],"redirect_url":"redirect.route.generated"}', $response->getContent());
    }


    public function testConfirmNotFound()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userRepository->method('findByConfirmationToken')->willReturn(null);
        $dependencies->userManager->expects($this->never())->method('confirm');
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->attributes->set('_config', 'theme');

        $token = '__TOKEN__';
        $this->expectException(NotFoundHttpException::class);
        $controller->confirmAction($request, $token);

    }

    public function testFinish()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('getTemplate')->willReturnCallback(function($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('reset_password_finish', $action);

            return 'finish.html.twig';
        });

        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->attributes->set('_config', 'theme');

        $response = $controller->finishAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('finish.html.twig.managed.rendered', $response->getContent());

    }
}

class ResetPasswordControllerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var UserRepository|MockObject */
    public $userRepository;

    /** @var TemplateResolver|MockObject */
    public $templateResolver;

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
}

class ResetPasswordControllerMock extends ResetPasswordController
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
}
