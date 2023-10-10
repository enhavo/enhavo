<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;


use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Enhavo\Bundle\UserBundle\Controller\RegistrationController;
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

class RegistrationControllerTest //extends TestCase
{
    private function createInstance(RegistrationControllerTestDependencies $dependencies)
    {
        return new RegistrationControllerMock(
            $dependencies->userManager,
            $dependencies->userRepository,
            $dependencies->templateResolver,
            $dependencies->userFactory,
            $dependencies->errorResolver
        );
    }

    private function createDependencies(): RegistrationControllerTestDependencies
    {
        $dependencies = new RegistrationControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateResolver = $this->getMockBuilder(TemplateResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateResolver->method('resolve')->willReturnCallback(function ($template) {
            return $template .'.managed';
        });
        $dependencies->userFactory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag([]);

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
        $dependencies->errorResolver = $this->getMockBuilder(FormErrorResolver::class)->disableOriginalConstructor()->getMock();
        $dependencies->errorResolver->method('getErrorFieldNames')->willReturn([]);
        $dependencies->errorResolver->method('getErrorMessages')->willReturn([]);


        return $dependencies;
    }

    public function testRegister()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userFactory->method('createNew')->willReturn(new User());

        $dependencies->userManager->expects($this->once())->method('register');
        $dependencies->userManager->method('resolve')->willReturn('register.html.twig');
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');
        $dependencies->form->expects($this->exactly(3))->method('handleRequest');
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->method('isXmlHttpRequest')->willReturn(false);
        $request->attributes->set('_config', 'theme');

        // unsubmitted no ajax
        $dependencies->isSubmitted = false;
        $request->attributes->set('_valid', false);
        $response = $controller->registerAction($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('register.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        // submitted invalid no ajax
        $dependencies->isSubmitted = true;
        $response = $controller->registerAction($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('register.html.twig.managed.rendered', $response->getContent());
        $this->assertEquals(400, $response->getStatusCode());

        // submitted valid no ajax
        $dependencies->isValid = true;
        $response = $controller->registerAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('redirect.route.generated', $response->getTargetUrl());
    }

    public function testRegisterAjax()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userFactory->method('createNew')->willReturn(new User());
        $dependencies->userManager->expects($this->once())->method('register');
        $dependencies->userManager->method('resolve')->willReturn('register.html.twig');
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->method('isXmlHttpRequest')->willReturn(true);
        $request->attributes->set('_config', 'theme');
        $dependencies->isSubmitted = false;
        $request->attributes->set('_valid', false);

        // unsubmitted no ajax
        $response = $controller->registerAction($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('register.html.twig.managed.rendered', $response->getContent());

        // submitted invalid ajax
        $dependencies->isSubmitted = true;
        $response = $controller->registerAction($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":true,"errors":{"fields":[],"messages":[]}}', $response->getContent());

        // submitted valid ajax
        $dependencies->isValid = true;
        $response = $controller->registerAction($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":false,"errors":[],"redirect_url":"redirect.route.generated"}', $response->getContent());
    }

    public function testCheck()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->method('resolve')->willReturnCallback(function($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('registration_check', $action);

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
        $dependencies->userManager->method('resolve')->willReturn('confirm.html.twig');
        $dependencies->userManager->expects($this->once())->method('getRedirectRoute')->willReturn('redirect.route');
        $dependencies->userRepository->method('findByConfirmationToken')->willReturn(new User());
        $dependencies->userManager->expects($this->once())->method('confirm');
        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $dependencies->request;
        $request->attributes->set('_config', 'theme');

        $token = '__TOKEN__';
        $response = $controller->confirmAction($request, $token);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('redirect.route.generated', $response->getTargetUrl());
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
        $dependencies->userManager->method('resolve')->willReturnCallback(function($config, $action) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('registration_finish', $action);

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

class RegistrationControllerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var UserRepository|MockObject */
    public $userRepository;

    /** @var TemplateResolver|MockObject */
    public $templateResolver;

    /** @var FactoryInterface|MockObject */
    public $userFactory;

    /** @var FormInterface|MockObject */
    public $form;

    /** @var Request|MockObject */
    public $request;

    /** @var FormErrorResolver|MockObject */
    public $errorResolver;

    public $isSubmitted = false;

    public $isValid = false;
}

class RegistrationControllerMock extends RegistrationController
{
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return new Response($view .'.rendered');
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $route .'.generated';
    }
}
