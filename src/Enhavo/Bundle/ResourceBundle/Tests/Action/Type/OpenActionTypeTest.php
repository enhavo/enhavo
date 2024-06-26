<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionLanguageExpression;
use Enhavo\Bundle\ResourceBundle\Action\Type\OpenActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OpenActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new OpenActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ActionLanguageExpression::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(OpenActionTypeDependencies $dependencies)
    {
        return new OpenActionType(
            $dependencies->translator,
            $dependencies->actionLanguageExpression,
            $dependencies->router
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->router->method('generate')->willReturn('http://localhost/open');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'open_route',
            'target' => '_blank',
            'view_key' => 'edit'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('open-action', $viewData['component']);
        $this->assertEquals('label.open', $viewData['label']);
        $this->assertEquals('http://localhost/open', $viewData['url']);
        $this->assertEquals('_blank', $viewData['target']);
        $this->assertEquals('edit', $viewData['key']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('open', $type->getType());
    }
}

class OpenActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ActionLanguageExpression|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
