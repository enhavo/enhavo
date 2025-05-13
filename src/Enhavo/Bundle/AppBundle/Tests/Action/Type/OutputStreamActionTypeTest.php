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

use Enhavo\Bundle\AppBundle\Action\Type\OutputStreamActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use PHPUnit\Framework\TestCase;

class OutputStreamActionTypeTest extends TestCase
{
    use UrlActionTypeFactoryTrait;

    private function createDependencies(): OutputStreamActionTypeDependencies
    {
        $dependencies = new OutputStreamActionTypeDependencies();

        return $dependencies;
    }

    private function createInstance(OutputStreamActionTypeDependencies $dependencies): OutputStreamActionType
    {
        return new OutputStreamActionType(
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [
            $this->createUrlActionType($this->createUrlActionTypeDependencies()),
        ], [
            'route' => 'stream_route',
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('modal-output-stream', $viewData['modalComponent']);
    }

    public function testName()
    {
        $this->assertEquals('output_stream', OutputStreamActionType::getName());
    }
}

class OutputStreamActionTypeDependencies
{
}
