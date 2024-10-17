<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use PHPUnit\Framework\TestCase;

class BaseActionTypeTest extends TestCase
{
    use BaseActionTypeFactoryTrait;

    public function createDependencies(): BaseActionTypeDependencies
    {
        return $this->createBaseActionTypeDependencies();
    }

    public function createInstance(BaseActionTypeDependencies $dependencies)
    {
        return $this->createBaseActionType($dependencies);
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);
        $action = new Action($instance, [], [
            'icon' => 'test_icon',
            'model' => 'TestModel',
            'label' => 'Test',
        ]);


        $data = $action->createViewData();
        $this->assertEquals('action-action' , $data['component']);
        $this->assertEquals('test_icon' , $data['icon']);
        $this->assertEquals('Test.translated', $data['label']);
        $this->assertEquals('TestModel' , $data['model']);
    }
}

