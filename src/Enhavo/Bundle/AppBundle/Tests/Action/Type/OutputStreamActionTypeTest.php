<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-04
 * Time: 10:22
 */

namespace Enhavo\Bundle\AppBundle\Tests\Action\Type;

use Enhavo\Bundle\AppBundle\Action\Type\OutputStreamActionType;
use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

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
