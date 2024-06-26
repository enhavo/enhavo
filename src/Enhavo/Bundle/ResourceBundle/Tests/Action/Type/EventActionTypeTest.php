<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionLanguageExpression;
use Enhavo\Bundle\ResourceBundle\Action\Type\EventActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new \Enhavo\Bundle\AppBundle\Tests\Action\Type\EventActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ActionLanguageExpression::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(\Enhavo\Bundle\AppBundle\Tests\Action\Type\EventActionTypeDependencies $dependencies)
    {
        return new EventActionType(
            $dependencies->translator,
            $dependencies->actionLanguageExpression
        );
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
    /** @var ActionLanguageExpression|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    public $requestStack;
}
