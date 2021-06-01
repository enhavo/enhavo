<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\DeleteActionType;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\RouterMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new DeleteActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->expressionLanguage = $this->getMockBuilder(ExpressionLanguage::class)->getMock();
        $dependencies->authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->router = new RouterMock();
        $dependencies->tokenManager = $this->getMockBuilder(CsrfTokenManager::class)->getMock();
        return $dependencies;
    }

    private function createInstance(DeleteActionTypeDependencies $dependencies)
    {
        return new DeleteActionType(
            $dependencies->translator,
            $dependencies->expressionLanguage,
            $dependencies->authorizationChecker,
            $dependencies->tokenStorage,
            $dependencies->requestStack,
            $dependencies->router,
            $dependencies->tokenManager
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->tokenManager->method('getToken')->willReturn(new TestCsrfToken());
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'delete_route'
        ]);

        $viewData = $action->createViewData(new ResourceMock());

        $this->assertEquals('delete-action', $viewData['component']);
        $this->assertEquals('label.delete', $viewData['label']);
        $this->assertEquals('csrfTok3n', $viewData['token']);
        $this->assertEquals('/delete_route?id=1', $viewData['url']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('delete', $type->getType());
    }
}

class DeleteActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $expressionLanguage;
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $authorizationChecker;
    /** @var TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $tokenStorage;
    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    public $requestStack;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
    /** @var CsrfTokenManager|\PHPUnit_Framework_MockObject_MockObject */
    public $tokenManager;
}

class TestCsrfToken
{
    public function getValue()
    {
        return 'csrfTok3n';
    }
}
