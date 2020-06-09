<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\AppBundle\Tests\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\Batch;
use Enhavo\Bundle\AppBundle\Batch\Type\FormBatchType;
use PHPUnit\Framework\TestCase;

class FormBatchTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new FormBatchTypeDependencies();
        return $dependencies;
    }

    private function createInstance(FormBatchTypeDependencies $dependencies)
    {
        return new FormBatchType();
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'form_route' => 'my_form_route',
        ]);

        $viewData = $batch->createViewData();

        $this->assertEquals([
            'modal' => [
                'component' => 'ajax-form-modal',
                'route' => 'my_form_route',
            ]
        ], $viewData);
    }
}

class FormBatchTypeDependencies
{

}
