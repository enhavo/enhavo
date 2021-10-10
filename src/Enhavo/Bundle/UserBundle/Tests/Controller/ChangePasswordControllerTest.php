<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Controller;


use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Enhavo\Bundle\UserBundle\Controller\ChangePasswordController;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordControllerTest //extends TestCase
{
    private function createInstance(ChangePasswordControllerTestDependencies $dependencies)
    {
        return new ChangePasswordControllerMock(
            $dependencies->viewHandler,
            $dependencies->viewFactory,
            $dependencies->userManager,
            $dependencies->translator
        );
    }

    private function createDependencies(): ChangePasswordControllerTestDependencies
    {
        $dependencies = new ChangePasswordControllerTestDependencies();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->userManager->method('getConfigKey')->willReturnCallback(function ($request) {
            return $request->attributes->get('_config');
        });
        $dependencies->viewHandler = $this->getMockBuilder(ViewHandler::class)->disableOriginalConstructor()->getMock();
        $dependencies->viewFactory = $this->getMockBuilder(ViewFactory::class)->disableOriginalConstructor()->getMock();
        $dependencies->request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $dependencies->request->attributes = new ParameterBag([]);
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();

        return $dependencies;
    }

    public function testChangeActionValid()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->attributes->set('_config', 'admin');
        $dependencies->userManager->method('createForm')->willReturnCallback(function ($config, $action, $user) use ($dependencies) {
            $this->assertEquals('admin', $config);
            $this->assertEquals('change_password', $action);
            $this->assertInstanceOf(UserInterface::class, $user);

            return $dependencies->form;
        });
        $dependencies->form->expects($this->once())->method('setData');
        $dependencies->form->expects($this->once())->method('handleRequest');
        $dependencies->form->expects($this->once())->method('isValid')->willReturn(true);
        $dependencies->userManager->expects($this->once())->method('update')->willReturnCallback(function ($user, $persist) {
            $this->assertEquals(false, $persist);
        });
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            $this->assertEquals('change_password.message.success', $message);
            return $message .'.translated';
        });
        $dependencies->viewFactory->expects($this->once())->method('create')->willReturnCallback(function ($type, $parameters) {
            $this->assertEquals('form', $type);
            $this->assertInstanceOf(UserInterface::class, $parameters['resource']);
            $this->assertInstanceOf(FormInterface::class, $parameters['form']);

            return new View();
        });
        $dependencies->viewHandler->expects($this->once())->method('handle')->willReturn(new Response());
        $dependencies->request->method('isMethod')->willReturnCallback(function ($method) {
            $this->assertEquals('POST', strtoupper($method));

            return true;
        });

        $controller = $this->createInstance($dependencies);
        $controller->hasUser = true;
        $response = $controller->changeAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);

    }

    public function testChangeActionInvalid()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->attributes->set('_config', 'admin');
        $dependencies->userManager->method('createForm')->willReturnCallback(function ($config, $action, $user) use ($dependencies) {
            $this->assertEquals('admin', $config);
            $this->assertEquals('change_password', $action);
            $this->assertInstanceOf(UserInterface::class, $user);

            return $dependencies->form;
        });
        $dependencies->form->expects($this->once())->method('setData');
        $dependencies->form->expects($this->once())->method('handleRequest');
        $dependencies->form->expects($this->once())->method('isValid')->willReturn(false);
        $dependencies->userManager->expects($this->never())->method('update');
        $dependencies->translator->method('trans')->willReturnCallback(function ($message) {
            $this->assertEquals('change_password.message.error', $message);
            return $message .'.translated';
        });
        $dependencies->viewFactory->expects($this->once())->method('create')->willReturn(new View());
        $dependencies->viewHandler->expects($this->once())->method('handle')->willReturn(new Response());
        $dependencies->request->method('isMethod')->willReturn(true);

        $controller = $this->createInstance($dependencies);
        $controller->hasUser = true;
        $response = $controller->changeAction($dependencies->request);
        $this->assertInstanceOf(Response::class, $response);

    }

    public function testChangeActionNoUser()
    {
        $dependencies = $this->createDependencies();
        $dependencies->request->attributes->set('_config', 'admin');

        $controller = $this->createInstance($dependencies);
        $this->expectException(AccessDeniedException::class);
        $controller->changeAction($dependencies->request);

    }
}

class ChangePasswordControllerTestDependencies
{
    /** @var ViewHandler|MockObject */
    public $viewHandler;

    /** @var ViewFactory|MockObject */
    public $viewFactory;

    /** @var UserManager|MockObject */
    public $userManager;

    /** @var FormInterface|MockObject */
    public $form;

    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var Request|MockObject */
    public $request;

    public $isSubmitted = false;

    public $isValid = false;
}

class ChangePasswordControllerMock extends ChangePasswordController
{
    public $hasUser = false;
    public $flashMessages = [];

    protected function getUser()
    {
        if ($this->hasUser) {
            return new User();
        }

        return null;
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
