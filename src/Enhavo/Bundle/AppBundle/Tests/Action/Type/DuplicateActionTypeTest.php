<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\DuplicateActionType;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ResourceMock;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Tests\Action\Type\BaseActionTypeFactoryTrait;
use PHPUnit\Framework\TestCase;

class DuplicateActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;
    use BaseActionTypeFactoryTrait;

    private function createDependencies()
    {
        $dependencies = new DuplicateActionTypeDependencies();
        return $dependencies;
    }

    private function createInstance(DuplicateActionTypeDependencies $dependencies)
    {
        return new DuplicateActionType();
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createBaseActionType($this->createBaseActionTypeDependencies()),
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),

        ], [
            'route' => 'duplicate_route'
        ]);

        $viewData = $action->createViewData(new ResourceMock(1));

        $this->assertEquals('label.duplicate.translated', $viewData['label']);
        $this->assertEquals('/duplicate_route?id=1', $viewData['url']);
    }

    public function testName()
    {
        $this->assertEquals('duplicate', DuplicateActionType::getName());
    }
}

class DuplicateActionTypeDependencies
{
}
