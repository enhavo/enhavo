<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
