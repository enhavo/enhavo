<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;


use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\UserBundle\Controller\UserController;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserControllerTest extends TestCase
{
    private function createInstance(UserControllerTestDependencies $dependencies)
    {
        return new UserControllerMock(
            $dependencies->userManager,
            $dependencies->templateManager,
            $dependencies->translator
        );
    }

    private function createDependencies(): UserControllerTestDependencies
    {
        $dependencies = new UserControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateManager = $this->getMockBuilder(TemplateManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->templateManager->method('getTemplate')->willReturnCallback(function ($template) {
            return $template .'.managed';
        });

        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            return $message .'.translated';
        });
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->form->method('isSubmitted')->willReturnCallback(function () use ($dependencies) {
            return $dependencies->isSubmitted;
        });
        $dependencies->form->method('isValid')->willReturnCallback(function () use ($dependencies) {
            return $dependencies->isValid;
        });

        return $dependencies;
    }

    public function testProfileAction()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->expects($this->exactly(3))->method('createForm')->willReturnCallback(function ($config, $action, $user) use ($dependencies) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('profile', $action);

            return $dependencies->form;
        });
        $dependencies->userManager->expects($this->exactly(3))->method('getTemplate')->willReturn('profile.html.twig');
        $dependencies->userManager->expects($this->exactly(1))->method('update');

        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->attributes = new ParameterBag();

        $request->attributes->set('_config', 'theme');

        // unsubmitted
        $response = $controller->profileAction($request);
        $this->assertEquals('profile.html.twig.managed.rendered', $response->getContent());

        // submitted invalid
        $dependencies->isSubmitted = true;
        $response = $controller->profileAction($request);
        $this->assertEquals('profile.html.twig.managed.rendered', $response->getContent());

        // submitted valid
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->profileAction($request);
        $this->assertEquals('profile.html.twig.managed.rendered', $response->getContent());
    }

    public function testProfileActionAjax()
    {
        $dependencies = $this->createDependencies();
        $dependencies->userManager->expects($this->exactly(3))->method('createForm')->willReturnCallback(function ($config, $action, $user) use ($dependencies) {
            $this->assertEquals('theme', $config);
            $this->assertEquals('profile', $action);

            return $dependencies->form;
        });
        $dependencies->userManager->expects($this->exactly(1))->method('getTemplate')->willReturn('profile.html.twig');
        $dependencies->userManager->expects($this->exactly(1))->method('update');

        $controller = $this->createInstance($dependencies);

        /** @var Request|MockObject $request */
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->attributes = new ParameterBag();
        $request->method('isXmlHttpRequest')->willReturn(true);
        $request->attributes->set('_config', 'theme');

        // unsubmitted
        $response = $controller->profileAction($request);
        $this->assertEquals('profile.html.twig.managed.rendered', $response->getContent());

        // submitted invalid
        $dependencies->isSubmitted = true;
        $response = $controller->profileAction($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":true,"errors":[],"message":"profile.update.error.translated"}', $response->getContent());

        // submitted valid
        $dependencies->isSubmitted = true;
        $dependencies->isValid = true;
        $response = $controller->profileAction($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"error":false,"errors":[],"message":"profile.update.success.translated"}', $response->getContent());
    }
}

class UserControllerTestDependencies
{
    /** @var UserManager|MockObject */
    public $userManager;

    /** @var TemplateManager|MockObject */
    public $templateManager;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var FormInterface|MockObject */
    public $form;

    public $isSubmitted = false;
    public $isValid = false;
}

class UserControllerMock extends UserController
{
    public $isGranted = false;

    protected function getUser()
    {
        return new User();
    }

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

    protected function addFlash(string $type, $message)
    {
        $this->flashMessages[$type] = $message;
    }
}
