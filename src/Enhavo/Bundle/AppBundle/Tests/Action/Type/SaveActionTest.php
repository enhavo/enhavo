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
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SaveActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new SaveActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(SaveActionTypeDependencies $dependencies)
    {
        return new SaveActionType($dependencies->translator);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
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
}
