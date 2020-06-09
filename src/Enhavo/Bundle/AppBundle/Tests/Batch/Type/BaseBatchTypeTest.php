<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 22:14
 */

namespace Enhavo\Bundle\AppBundle\Tests\Batch\Type;

use Enhavo\Bundle\AppBundle\Batch\Batch;
use Enhavo\Bundle\AppBundle\Batch\Type\BaseBatchType;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBatchTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new BaseBatchTypeDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        return $dependencies;
    }

    private function createInstance(BaseBatchTypeDependencies $dependencies)
    {
        return new BaseBatchType($dependencies->translator);
    }

    public function testViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function($trans) { return 'trans|'.$trans; });
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'label' => 'Base Label',
            'confirm_message' => 'Confirm Message',
            'position' => 1,
        ]);

        $viewData = $batch->createViewData();

        $this->assertEquals([
            'label' => 'trans|Base Label',
            'confirmMessage' => 'trans|Confirm Message',
            'position' => 1,
            'component' => 'batch-url',
        ], $viewData);
    }

    public function testPermission()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function($trans) { return 'trans|'.$trans; });
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'label' => 'Base Label',
            'permission' => 'ROLE_USER'
        ]);

        $this->assertEquals('ROLE_USER', $batch->getPermission());
    }

    public function testHidden()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturnCallback(function($trans) { return 'trans|'.$trans; });
        $type = $this->createInstance($dependencies);

        $batch = new Batch($type, [], [
            'label' => 'Base Label',
            'hidden' => true
        ]);

        $this->assertTrue($batch->isHidden());
    }
}

class BaseBatchTypeDependencies
{
    /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $translator;
}
