<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Batch\Type;

use AbstractBatchType;
use Batch;
use BatchTypeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception\BatchExecutionException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Type\AssignBatchType;
use Type\FormBatchType;

class AssignBatchTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new AssignBatchTypeDependencies();
        $dependencies->parent = new AssignBatchParentBatchType();
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $dependencies->formFactory = $this->getMockBuilder(FormFactoryInterface::class)->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(AssignBatchTypeDependencies $dependencies)
    {
        $type = new AssignBatchType($dependencies->requestStack, $dependencies->formFactory, $dependencies->translator, $dependencies->em);
        $type->setParent($dependencies->parent);
        return $type;
    }

    private function createRequestMock()
    {
        return $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
    }

    private function createFormMock()
    {
        return $this->getMockBuilder(FormInterface::class)->disableOriginalConstructor()->getMock();
    }

    public function testExecuteWithDataProperty()
    {
        $formMock = $this->createFormMock();
        $formMock->method('isValid')->willReturn(true);
        $formMock->method('getData')->willReturn(new AssignBatchData('Foobar'));

        $dependencies = $this->createDependencies();
        $dependencies->requestStack->method('getMainRequest')->willReturn($this->createRequestMock());
        $dependencies->formFactory->expects($this->once())->method('create')->willReturn($formMock);
        $dependencies->em->expects($this->once())->method('flush');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], [
            'data_property' => 'newName',
            'property' => 'name',
            'form' => 'AnyForm'
        ]);

        $resources = [new AssignBatchResource(), new AssignBatchResource()];

        $batch->execute($resources);

        $this->assertEquals('Foobar',$resources[0]->getName());
        $this->assertEquals('Foobar',$resources[1]->getName());
    }

    public function testExecuteWithoutDataProperty()
    {
        $assignData = new AssignBatchData('Foobar');

        $formMock = $this->createFormMock();
        $formMock->method('isValid')->willReturn(true);
        $formMock->method('getData')->willReturn($assignData);

        $dependencies = $this->createDependencies();
        $dependencies->requestStack->method('getMainRequest')->willReturn($this->createRequestMock());
        $dependencies->formFactory->expects($this->once())->method('create')->willReturn($formMock);
        $dependencies->em->expects($this->once())->method('flush');
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], [
            'property' => 'name',
            'form' => 'AnyForm'
        ]);

        $resources = [new AssignBatchResource(), new AssignBatchResource()];

        $batch->execute($resources);

        $this->assertTrue($assignData === $resources[0]->getName());
        $this->assertTrue($assignData === $resources[1]->getName());
    }

    public function testInvalid()
    {
        $this->expectException(BatchExecutionException::class);

        $formMock = $this->createFormMock();
        $formMock->method('isValid')->willReturn(false);

        $dependencies = $this->createDependencies();
        $dependencies->requestStack->method('getMainRequest')->willReturn($this->createRequestMock());
        $dependencies->translator->method('trans')->willReturnCallback(function($trans) { return $trans; });
        $dependencies->formFactory->method('create')->willReturn($formMock);
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], [
            'property' => 'name',
            'form' => 'AnyForm'
        ]);

        $batch->execute([]);
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [$dependencies->parent], [
            'property' => 'name',
            'form' => 'AnyForm',
            'route' => 'some_route',
            'route_parameters' => ['parameter' => 'value']
        ]);

        $viewData = $batch->createViewData();

        $this->assertEquals([
            'route' => 'some_route',
            'routeParameters' => ['parameter' => 'value']
        ], $viewData);
    }

    public function testGetName()
    {
        $this->assertEquals('assign', AssignBatchType::getName());
    }

    public function testParentName()
    {
        $this->assertEquals(FormBatchType::class, AssignBatchType::getParentType());
    }
}

class AssignBatchTypeDependencies
{
    /** @var BatchTypeInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $parent;
    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    public $requestStack;
    /** @var FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $formFactory;
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $em;
}

class AssignBatchParentBatchType extends AbstractBatchType
{

}

class AssignBatchResource
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
}

class AssignBatchData
{
    private $newName;

    public function __construct($newName)
    {
        $this->newName = $newName;
    }

    public function getNewName()
    {
        return $this->newName;
    }

    public function setNewName($newName): void
    {
        $this->newName = $newName;
    }
}
