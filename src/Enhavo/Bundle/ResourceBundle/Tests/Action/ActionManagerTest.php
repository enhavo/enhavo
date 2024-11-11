<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 09:31
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ActionManagerTest extends TestCase
{
    private function createDependencies(): ActionManagerDependencies
    {
        $dependencies = new ActionManagerDependencies();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(ActionManagerDependencies $dependencies): ActionManager
    {
        return new ActionManager(
            $dependencies->checker,
            $dependencies->factory,
        );
    }

    public function testGetActions()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $action = $this->getMockBuilder(Action::class)->disableOriginalConstructor()->getMock();
                $action->method('isEnabled')->willReturn(true);
                $action->method('getPermission')->willReturn(true);
                $action->method('createViewData')->willReturn(['name' => 'test']);
                return $action;
            }
        });
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(1, $actions);
        $this->assertEquals('test', $actions['create']->createViewData()['name']);
    }

    public function testNotEnabled()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $action = $this->getMockBuilder(Action::class)->disableOriginalConstructor()->getMock();
                $action->method('isEnabled')->willReturn(false);
                $action->method('getPermission')->willReturn(true);
                return $action;
            }
        });
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(0, $actions);
    }

    public function testNoPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $action = $this->getMockBuilder(Action::class)->disableOriginalConstructor()->getMock();
                $action->method('isEnabled')->willReturn(true);
                $action->method('getPermission')->willReturn(true);
                return $action;
            }
        });
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(0, $actions);
    }

    public function testEmptyPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->factory->method('create')->willReturnCallback(function ($options, $key) {
            if ($options['type'] === 'test') {
                $action = $this->getMockBuilder(Action::class)->disableOriginalConstructor()->getMock();
                $action->method('isEnabled')->willReturn(true);
                $action->method('getPermission')->willReturn(null);
                return $action;
            }
        });
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
            ]
        ]);

        $this->assertCount(1, $actions);
    }

}

class ActionManagerDependencies
{
    public FactoryInterface|MockObject $factory;
    public AuthorizationCheckerInterface|MockObject $checker;
}
