<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Action;
use Enhavo\Bundle\AppBundle\Action\Type\ModalActionType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ModalActionTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new ModalActionTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->actionLanguageExpression = $this->getMockBuilder(ResourceExpressionLanguage::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(ModalActionTypeDependencies $dependencies)
    {
        return new ModalActionType(
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
            'label' => 'my_label',
            'modal' => [
                'type' => 'modal-type',
                'option' => 'option-value'
            ]
        ]);
        $viewData = $action->createViewData();

        $this->assertEquals('modal-action', $viewData['component']);
        $this->assertEquals('my_label', $viewData['label']);
        $this->assertEquals('modal-type', $viewData['modal']['type']);
        $this->assertEquals('option-value', $viewData['modal']['option']);
    }

    public function testType()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);
        $this->assertEquals('modal', $type->getType());
    }
}

class ModalActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
}
