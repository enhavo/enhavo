<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\CloseActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class CloseActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new CloseActionTypeTestDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(CloseActionTypeTestDependencies $dependencies)
    {
        return new CloseActionType($dependencies->translator);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $type = $this->createInstance($dependencies);

        $action = new Action($type, []);

        $viewData = $action->createViewData();

        $this->assertEquals('close-action', $viewData['component']);
        $this->assertEquals('label.close', $viewData['label']);
        $this->assertEquals('close', $viewData['icon']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('close', $type->getType());
    }
}

class CloseActionTypeTestDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
}
