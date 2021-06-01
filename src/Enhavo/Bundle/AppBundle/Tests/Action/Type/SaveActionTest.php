<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\SaveActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SaveActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new SaveActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->expressionLanguage = $this->getMockBuilder(ExpressionLanguage::class)->getMock();
        $dependencies->authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(SaveActionTypeDependencies $dependencies)
    {
        return new SaveActionType(
            $dependencies->translator,
            $dependencies->expressionLanguage,
            $dependencies->authorizationChecker,
            $dependencies->tokenStorage,
            $dependencies->requestStack,
            $dependencies->router
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->router->method('generate')->willReturn('url');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, []);

        $viewData = $action->createViewData();

        $this->assertEquals('save-action', $viewData['component']);
        $this->assertEquals('label.save', $viewData['label']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('save', $type->getType());
    }
}

class SaveActionTypeDependencies
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
}
