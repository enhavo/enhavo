<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Batch\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\Type\FormBatchType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;

class FormBatchTypeTest extends TestCase
{
    private function createDependencies(): FormBatchTypeDependencies
    {
        $dependencies = new FormBatchTypeDependencies();
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->vueForm = $this->getMockBuilder(VueForm::class)->disableOriginalConstructor()->getMock();
        $dependencies->expressionLanguage = new ResourceExpressionLanguage();
        return $dependencies;
    }

    private function createInstance(FormBatchTypeDependencies $dependencies): FormBatchType
    {
        return new FormBatchType(
            $dependencies->vueForm,
            $dependencies->formFactory,
            $dependencies->expressionLanguage,
        );
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->vueForm->method('createData')->willReturn(['form' => 'data']);

        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'form' => 'SampleForm'
        ]);

        $viewData = $batch->createViewData();

        $this->assertEquals(['form' => 'data'], $viewData['form']);
    }
}

class FormBatchTypeDependencies
{
    public EntityRepository|MockObject $repository;
    public VueForm|MockObject $vueForm;
    public FormFactoryInterface|MockObject $formFactory;
    public ResourceExpressionLanguage|MockObject $expressionLanguage;
}
