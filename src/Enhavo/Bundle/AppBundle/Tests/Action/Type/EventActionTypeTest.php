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
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new EventActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->expressionLanguage = $this->getMockBuilder(ExpressionLanguage::class)->getMock();
        $dependencies->authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();;
        return $dependencies;
    }

    private function createInstance(EventActionTypeDependencies $dependencies)
    {
        return new EventActionType(
            $dependencies->translator,
            $dependencies->expressionLanguage,
            $dependencies->authorizationChecker,
            $dependencies->tokenStorage,
            $dependencies->requestStack
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
    /** @var ExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $expressionLanguage;
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $authorizationChecker;
    /** @var TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $tokenStorage;
    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    public $requestStack;
}
