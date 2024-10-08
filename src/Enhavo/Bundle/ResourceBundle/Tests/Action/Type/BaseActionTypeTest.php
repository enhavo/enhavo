<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\Type\BaseActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseActionTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new BaseActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator->method('trans')->willReturnCallback(function ($label) {
            return $label . '.translated';
        });

        $dependencies->expressionLanguage = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    public function createInstance(BaseActionTypeDependencies $dependencies)
    {
        $instance = new BaseActionType(
            $dependencies->translator,
            $dependencies->expressionLanguage,
        );

        return $instance;
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

