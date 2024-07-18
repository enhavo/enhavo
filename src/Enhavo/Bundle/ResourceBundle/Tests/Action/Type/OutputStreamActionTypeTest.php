<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\OutputStreamActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OutputStreamActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new \Enhavo\Bundle\AppBundle\Tests\Action\Type\OutputStreamActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(\Enhavo\Bundle\AppBundle\Tests\Action\Type\OutputStreamActionTypeDependencies $dependencies)
    {
        return new OutputStreamActionType(
            $dependencies->translator,
            $dependencies->actionLanguageExpression,
            $dependencies->router
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->router->method('generate')->willReturn('http://localhost/stream');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'stream_route',
            'label' => 'my Label'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('modal-action', $viewData['component']);
        $this->assertEquals('my Label', $viewData['label']);
        $this->assertEquals('http://localhost/stream', $viewData['url']);
        $this->assertEquals('output-stream', $viewData['modal']['component']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('output_stream', $type->getType());
    }
}

class OutputStreamActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
