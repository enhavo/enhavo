<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\ModalActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Tests\Action\Type\BaseActionTypeFactoryTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class ModalActionTypeTest extends TestCase
{
    use BaseActionTypeFactoryTrait;

    private function createDependencies(): ModalActionTypeDependencies
    {
        $dependencies = new ModalActionTypeDependencies();
        return $dependencies;
    }

    private function createInstance(ModalActionTypeDependencies $dependencies): ModalActionType
    {
        return new ModalActionType(
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [], [
            'modal' => [
                'model' => 'TestModal'
            ]
        ]);
        $viewData = $action->createViewData();

        $this->assertEquals([
            'model' => 'TestModal'
        ], $viewData['modal']);
    }

    public function testName()
    {
        $this->assertEquals('modal', ModalActionType::getName());
    }
}

class ModalActionTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var ResourceExpressionLanguage|\PHPUnit_Framework_MockObject_MockObject */
    public $actionLanguageExpression;
}
