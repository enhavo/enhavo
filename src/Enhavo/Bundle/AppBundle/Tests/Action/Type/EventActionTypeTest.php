<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\EventActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Tests\Action\Type\BaseActionTypeFactoryTrait;
use PHPUnit\Framework\TestCase;

class EventActionTypeTest extends TestCase
{
    use BaseActionTypeFactoryTrait;

    private function createDependencies(): EventActionTypeDependencies
    {
        $dependencies = new EventActionTypeDependencies();
        return $dependencies;
    }

    private function createInstance(EventActionTypeDependencies $dependencies): EventActionType
    {
        return new EventActionType(
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createBaseActionType($this->createBaseActionTypeDependencies()),
        ], [
            'event' => 'my-event',
            'icon' => 'event',
            'label' => 'label',
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('EventAction', $viewData['model']);
        $this->assertEquals('my-event', $viewData['event']);
    }

    public function testName()
    {
        $this->assertEquals('event', EventActionType::getName());
    }
}

class EventActionTypeDependencies
{

}
