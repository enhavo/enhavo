<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\UpdateActionType;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\RouterMock;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UpdateActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new UpdateActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = new RouterMock();
        return $dependencies;
    }

    private function createInstance(UpdateActionTypeDependencies $dependencies)
    {
        return new UpdateActionType(
            $dependencies->translator,
            $dependencies->actionLanguageExpression,
            $dependencies->router
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'edit_route'
        ]);

        $viewData = $action->createViewData(new ResourceMock());

        $this->assertEquals('open-action', $viewData['component']);
        $this->assertEquals('label.edit', $viewData['label']);
        $this->assertEquals('/edit_route?id=1', $viewData['url']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('update', $type->getType());
    }
}

class UpdateActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
