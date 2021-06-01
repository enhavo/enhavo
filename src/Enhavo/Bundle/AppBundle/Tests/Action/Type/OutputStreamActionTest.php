<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\OutputStreamActionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OutputStreamActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new OutputStreamActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->expressionLanguage = $this->getMockBuilder(ExpressionLanguage::class)->getMock();
        $dependencies->authorizationChecker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->router = $this->getMockBuilder(RouterInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(OutputStreamActionTypeDependencies $dependencies)
    {
        return new OutputStreamActionType(
            $dependencies->translator,
            $dependencies->expressionLanguage,
            $dependencies->authorizationChecker,
            $dependencies->tokenStorage,
            $dependencies->requestStack,
            $dependencies->router
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function ($value) {return $value;});
        $dependencies->router->method('generate')->willReturn('http://localhost/stream');
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            'route' => 'stream_route',
            'label' => 'my Label'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('modal-action', $viewData['component']);
        $this->assertEquals('my Label', $viewData['label']);
        $this->assertEquals('http://localhost/stream', $viewData['url']);
        $this->assertEquals('output-stream', $viewData['modal']['component']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('output_stream', $type->getType());
    }
}

class OutputStreamActionTypeDependencies
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
    /** @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $router;
}
