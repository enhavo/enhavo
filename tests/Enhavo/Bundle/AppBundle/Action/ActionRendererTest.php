<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 30/09/16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Enhavo\Bundle\AppBundle\Type\CollectorInterface;

class ActionRendererTest extends \PHPUnit_Framework_TestCase
{
    private function getTypeInterfaceMock()
    {
        $mock = $this->getMockBuilder(ActionInterface::class)->getMock();
        $mock->expects($this->once())->method('render')->willReturnCallback(function($options){
            return(implode(' ', $options));
        });
        return $mock;
    }

    private function getCollectorMock()
    {
        $mock = $this->getMockBuilder(CollectorInterface::class)
            ->getMock();
        $mock->expects($this->once())->method('getType')->willReturn($this->getTypeInterfaceMock());
        return $mock;
    }

    private function getCollectorMockName()
    {
        $mock = $this->getMockBuilder(CollectorInterface::class)
            ->getMock();
        return $mock;
    }

    public function testRender()
    {
        $collector = $this->getCollectorMock();
        $actionRenderer = new ActionRenderer($collector);

        $string = $actionRenderer->render('type', ['arg1', 'arg2', 'arg3']);

        static::assertEquals('arg1 arg2 arg3', $string);
    }

    public function testGetName()
    {
        $collector = $this->getCollectorMockName();
        $actionRenderer = new ActionRenderer($collector);

        $name = $actionRenderer->getName();

        static::assertEquals('action_render', $name);
    }
}