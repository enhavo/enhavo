<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\CloseActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\Type\BaseActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class CloseActionTypeTest extends TestCase
{
    private function createDependencies(): CloseActionTypeTestDependencies
    {
        $dependencies = new CloseActionTypeTestDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->resourceExpressionLanguage = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(CloseActionTypeTestDependencies $dependencies): CloseActionType
    {
        return new CloseActionType();
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);


        $baseType = new BaseActionType($dependencies->translator, $dependencies->resourceExpressionLanguage);
        $action = new Action($type, [$baseType], []);

        $viewData = $action->createViewData();

        $this->assertEquals('CloseAction', $viewData['model']);
    }

    public function testName()
    {
        $this->assertEquals('close', CloseActionType::getName());
    }
}

class CloseActionTypeTestDependencies
{
    public ResourceExpressionLanguage|MockObject $resourceExpressionLanguage;
    public TranslatorInterface|MockObject $translator;
}
