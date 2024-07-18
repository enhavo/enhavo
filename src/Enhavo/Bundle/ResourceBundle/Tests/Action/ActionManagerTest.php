<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 09:31
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Action;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ActionManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new \Enhavo\Bundle\AppBundle\Tests\Action\Dependencies();
        $dependencies->collector = $this->getMockBuilder(TypeCollector::class)->disableOriginalConstructor()->getMock();
        $dependencies->checker = $this->getMockBuilder(AuthorizationCheckerInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(\Enhavo\Bundle\AppBundle\Tests\Action\Dependencies $dependencies)
    {
        return new ActionManager($dependencies->collector, $dependencies->checker);
    }

    public function testGetActions()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->collector->method('getType')->willReturn(new \Enhavo\Bundle\AppBundle\Tests\Action\TestActionType());
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
                'option_key' => 'option_value'
            ]
        ]);

        $this->assertArrayHasKey('create', $actions);
        $this->assertCount(1, $actions);
        $this->assertEquals('test_value', $actions['create']->createViewData()['data']);
    }

    public function testCreateActionsViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->collector->method('getType')->willReturnCallback(function ($type) {
            if ($type === 'test') {
                return new \Enhavo\Bundle\AppBundle\Tests\Action\TestActionType();
            } elseif ($type === 'other_test') {
                return new \Enhavo\Bundle\AppBundle\Tests\Action\OtherTestActionType();
            }
            return null;
        });
        $manager = $this->createInstance($dependencies);

        $actions = $manager->createActionsViewData([
            'create' => [
                'type' => 'test',
                'option_key' => 'option_value'
            ],
            'update' => [
                'type' => 'other_test',
                'option_key' => 'option_value'
            ]
        ]);

        $this->assertCount(2, $actions);
        $this->assertEquals('test_value', $actions[0]['data']);
        $this->assertEquals('view_data', $actions[1]['view']);
    }

    public function testMissingType()
    {
        $this->expectException(\Enhavo\Bundle\AppBundle\Exception\TypeMissingException::class);
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);

        $actions = $manager->createActionsViewData([
            'create' => [
                'option_key' => 'option_value'
            ],
        ]);
    }

    public function testHiddenCheck()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(true);
        $dependencies->collector->method('getType')->willReturn(new \Enhavo\Bundle\AppBundle\Tests\Action\TestActionType(true));
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
                'option_key' => 'option_value'
            ]
        ]);

        $this->assertCount(0, $actions);
    }

    public function testPermissionCheck()
    {
        $dependencies = $this->createDependencies();
        $dependencies->checker->method('isGranted')->willReturn(false);
        $dependencies->collector->method('getType')->willReturn(new \Enhavo\Bundle\AppBundle\Tests\Action\TestActionType());
        $manager = $this->createInstance($dependencies);

        $actions = $manager->getActions([
            'create' => [
                'type' => 'test',
                'option_key' => 'option_value'
            ]
        ]);

        $this->assertCount(0, $actions);
    }
}

class Dependencies
{
    /** @var TypeCollector|\PHPUnit_Framework_MockObject_MockObject */
    public $collector;
    /** @var AuthorizationCheckerInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $checker;
}

class TestTabType implements ActionTypeInterface
{
    /** @var boolean */
    private $hidden;
    /** @var string */
    private $role;

    /**
     * TestActionType constructor.
     * @param bool $hidden
     * @param string $role
     */
    public function __construct(bool $hidden = false, string $role = 'ROLE_TEST')
    {
        $this->hidden = $hidden;
        $this->role = $role;
    }

    public function createViewData(array $options, $resource = null)
    {
        return ['data' => 'test_value'];
    }

    public function getPermission(array $options, $resource = null)
    {
        return $this->role;
    }

    public function isHidden(array $options, $resource = null)
    {
        return $this->hidden;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'option_key' => 'option_value'
        ]);
    }

    public function getType()
    {
        return 'test';
    }
}

class OtherTestActionType extends TestTabType
{
    public function createViewData(array $options, $resource = null)
    {
        return ['view' => 'view_data'];
    }

    public function getType()
    {
        return 'other_test';
    }
}
