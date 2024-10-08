<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\FilterActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Tests\Action\Type\BaseActionTypeFactoryTrait;
use PHPUnit\Framework\TestCase;

class FilterActionTypeTest extends TestCase
{
    use BaseActionTypeFactoryTrait;

    private function createDependencies(): FilterActionTypeDependencies
    {
        $dependencies = new FilterActionTypeDependencies();
        return $dependencies;
    }

    private function createInstance(FilterActionTypeDependencies $dependencies): FilterActionType
    {
        return new FilterActionType(
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createBaseActionType($this->createBaseActionTypeDependencies()),
        ], [

        ]);
        $viewData = $action->createViewData();

        $this->assertEquals('FilterAction', $viewData['model']);
    }

    public function testName()
    {
        $this->assertEquals('filter', FilterActionType::getName());
    }
}

class FilterActionTypeDependencies
{
}
