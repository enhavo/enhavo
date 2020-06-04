<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\DuplicateActionType;
use Enhavo\Bundle\AppBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\RouterMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DuplicateActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new DuplicateActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->router = new RouterMock();
        return $dependencies;
    }

    private function createInstance(DuplicateActionTypeDependencies $dependencies)
    {
        return new DuplicateActionType($dependencies->translator, $dependencies->router);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'duplicate_route'
        ]);

        $viewData = $action->createViewData(new ResourceMock());

        $this->assertEquals('duplicate-action', $viewData['component']);
        $this->assertEquals('label.duplicate', $viewData['label']);
        $this->assertEquals('/duplicate_route?id=1', $viewData['url']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('duplicate', $type->getType());
    }
}

class DuplicateActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
