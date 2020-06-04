<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\EventActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new EventActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();;
        return $dependencies;
    }

    private function createInstance(EventActionTypeDependencies $dependencies)
    {
        return new EventActionType($dependencies->translator);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'icon' => 'event-icon',
            'label' => 'event-label',
            'event' => 'my-event'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('event-action', $viewData['component']);
        $this->assertEquals('event-label', $viewData['label']);
        $this->assertEquals('event-icon', $viewData['icon']);
        $this->assertEquals('my-event', $viewData['event']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('event', $type->getType());
    }
}

class EventActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
}
