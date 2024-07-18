<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\DownloadActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DownloadActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new DownloadActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(DownloadActionTypeDependencies $dependencies)
    {
        return new DownloadActionType(
            $dependencies->translator,
            $dependencies->actionLanguageExpression,
            $dependencies->router
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->router->method('generate')->willReturn('http://localhost/download');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'download_route'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('download-action', $viewData['component']);
        $this->assertEquals('label.download', $viewData['label']);
        $this->assertEquals('http://localhost/download', $viewData['url']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('download', $type->getType());
    }
}

class DownloadActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
