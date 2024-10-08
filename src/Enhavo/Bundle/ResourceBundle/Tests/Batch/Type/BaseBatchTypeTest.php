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
use Enhavo\Bundle\ResourceBundle\Batch\Type\BaseBatchType;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\RouterMock;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\TranslatorMock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBatchTypeTest extends TestCase
{
    private function createDependencies(): BaseBatchTypeDependencies
    {
        $dependencies = new BaseBatchTypeDependencies();
        $dependencies->translator = new TranslatorMock('.trans');
        $dependencies->routeResolver = $this->getMockBuilder(RouteResolverInterface::class)->getMock();
        $dependencies->router = new RouterMock();
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    private function createInstance(BaseBatchTypeDependencies $dependencies): BaseBatchType
    {
        return new BaseBatchType(
            $dependencies->translator,
            $dependencies->routeResolver,
            $dependencies->router,
        );
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'label' => 'Base Label',
            'confirm_message' => 'Confirm Message',
            'route' => 'batch_route',
            'route_parameters' => ['key' => 'value'],
            'position' => 3,
        ]);

        $viewData = $batch->createViewData();

        $this->assertEquals('Base Label.trans', $viewData['label']);
        $this->assertEquals('Confirm Message.trans', $viewData['confirmMessage']);
        $this->assertEquals('/batch_route?key=value', $viewData['url']);
        $this->assertEquals(3, $viewData['position']);
    }

    public function testPermission()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'label' => 'Base Label',
            'permission' => 'ROLE_USER'
        ]);

        $this->assertEquals('ROLE_USER', $batch->getPermission($dependencies->repository));
    }

    public function testEnabled()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'label' => 'Base Label',
            'enabled' => false
        ]);

        $this->assertFalse($batch->isEnabled());
    }
}

class BaseBatchTypeDependencies
{
    public TranslatorInterface|MockObject $translator;
    public RouteResolverInterface|MockObject $routeResolver;
    public RouterInterface|MockObject $router;
    public EntityRepository|MockObject $repository;
}
