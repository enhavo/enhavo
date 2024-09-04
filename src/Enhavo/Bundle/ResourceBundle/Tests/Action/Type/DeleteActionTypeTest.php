<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\RouterMock;
use Enhavo\Bundle\ResourceBundle\Action\Type\DeleteActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Contracts\Translation\TranslatorInterface;

class DeleteActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new \Enhavo\Bundle\AppBundle\Tests\Action\Type\DeleteActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = new RouterMock();
        $dependencies->tokenManager = $this->getMockBuilder(CsrfTokenManager::class)->getMock();
        return $dependencies;
    }

    private function createInstance(\Enhavo\Bundle\AppBundle\Tests\Action\Type\DeleteActionTypeDependencies $dependencies)
    {
        return new DeleteActionType(
            $dependencies->translator,
            $dependencies->actionLanguageExpression,
            $dependencies->router,
            $dependencies->tokenManager
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->tokenManager->method('getToken')->willReturn(new \Enhavo\Bundle\AppBundle\Tests\Action\Type\TestCsrfToken());
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
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
    /** @var CsrfTokenManager|\PHPUnit_Framework_MockObject_MockObject */
    public $tokenManager;
}

class TestCsrfToken extends \Symfony\Component\Security\Csrf\CsrfToken
{
    public function __construct()
    {
    }

    public function getValue(): string
    {
        return 'csrfTok3n';
    }
}
