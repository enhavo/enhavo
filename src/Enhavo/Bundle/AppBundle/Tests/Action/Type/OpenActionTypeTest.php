<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use PHPUnit\Framework\TestCase;

class OpenActionTypeTest extends TestCase
{
    private function createDependencies(): OpenActionTypeDependencies
    {
        $dependencies = new OpenActionTypeDependencies();
        return $dependencies;
    }

    private function createInstance(OpenActionTypeDependencies $dependencies): OpenActionType
    {
        return new OpenActionType(
        );
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $action = new Action($type, [], [
            'route' => 'open_route',
            'target' => '_blank',
            'frame_key' => 'edit'
        ]);

        $viewData = $action->createViewData();

        $this->assertEquals('_blank', $viewData['target']);
        $this->assertEquals('edit', $viewData['frameKey']);
    }

    public function testName()
    {
        $this->assertEquals('open', OpenActionType::getName());
    }
}

class OpenActionTypeDependencies
{
}
