<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;


use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Controller\ResetPasswordController;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
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

class ResetPasswordControllerTest extends TestCase
{
    private function createInstance(ResetPasswordControllerTestDependencies $dependencies)
    {
        return new ResetPasswordControllerMock(
            $dependencies->userManager,
            $dependencies->userRepository,
            $dependencies->templateManager,
            $dependencies->userFactory,
            $dependencies->translator
        );
    }

    private function createDependencies(): ResetPasswordControllerTestDependencies
    {
        $dependencies = new ResetPasswordControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
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

        $dependencies->userManager->method('createForm')->willReturn(
            $dependencies->form
        );

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
        $dependencies->userFactory->method('createNew')->willReturn(new User());
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
        $this->assertEquals('reset.form.error.invalid-user.translated', $controller->flashMessages['error']);
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
        $this->assertEquals('{"error":true,"errors":[]}', $response->getContent());


        // submitted no errors user found
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":false,"errors":[],"message":"reset.message.success.translated","redirect_url":"redirect.route.generated"}', $response->getContent());

        // submitted no errors no user
        $dependencies->userExists = false;
        $response = $controller->requestAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('{"error":true,"errors":[],"message":"reset.form.error.invalid-user.translated"}', $response->getContent());
        $this->assertEquals('reset.form.error.invalid-user.translated', $controller->flashMessages['error']);
    }
}

class ResetPasswordControllerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

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

    public $isSubmitted = false;

    public $isValid = false;

    public $userExists = true;
}

class ResetPasswordControllerMock extends ResetPasswordController
{
    public $flashMessages = [];

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        return new Response($view .'.rendered');
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $route .'.generated';
    }

    protected function getFlashMessages()
    {
        return $this->flashMessages;
    }

    protected function addFlash(string $type, $message)
    {
        $this->flashMessages[$type] = $message;
    }
}
