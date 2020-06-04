<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\CreateActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new CreateActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(CreateActionTypeDependencies $dependencies)
    {
        return new CreateActionType($dependencies->translator, $dependencies->router);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->router->method('generate')->willReturn('http://localhost/create');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'create_route'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('open-action', $viewData['component']);
        $this->assertEquals('label.create', $viewData['label']);
        $this->assertEquals('http://localhost/create', $viewData['url']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('create', $type->getType());
    }
}

class CreateActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
