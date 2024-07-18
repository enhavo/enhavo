<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\PreviewActionType;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\RouterMock;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreviewActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new PreviewActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = new RouterMock();
        return $dependencies;
    }

    private function createInstance(PreviewActionTypeDependencies $dependencies)
    {
        return new PreviewActionType(
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
            'route' => 'preview_route',
        ]);

        $viewData = $action->createViewData(new ResourceMock());

        $this->assertEquals('preview-action', $viewData['component']);
        $this->assertEquals('label.preview', $viewData['label']);
        $this->assertEquals('/preview_route?id=1', $viewData['url']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('preview', $type->getType());
    }
}

class PreviewActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
