<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget\Type;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Dashboard\Type\BaseDashboardWidgetType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WidgetTypeTest extends TestCase
{
    public function testPermission()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $options = [
            'permission' => 'ROLE_TEST'
        ];

        $this->assertEquals('ROLE_TEST', $instance->getPermission($options));
    }

    public function testHidden()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $this->assertTrue($instance->isHidden([
            'hidden' => true
        ]));

        $this->assertFalse($instance->isHidden([
            'hidden' => false
        ]));
    }

    public function testConfigureOptions()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);

        $this->expectException(MissingOptionsException::class);
        $optionsResolver->resolve([]);
    }

    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translator->method('trans')->willReturn('translated');
        $instance = $this->createInstance($dependencies);

        $viewData = new ViewData();
        $instance->createViewData([
            'label' => 'label',
            'component' => 'component123',
            'translation_domain' => null
        ], $viewData);

        $this->assertEquals('translated', $viewData->get('label'));
        $this->assertEquals('component123', $viewData->get('component'));
    }

    private function createInstance(WidgetTypeTestDependencies $dependencies)
    {
        return new BaseDashboardWidgetType($dependencies->translator);
    }

    private function createDependencies()
    {
        $dependencies = new WidgetTypeTestDependencies();
        $dependencies->translator = $this->createTranslatorMock();

        return $dependencies;
    }

    private function createTranslatorMock()
    {
        return $this->createMock(TranslatorInterface::class);
    }
}

class WidgetTypeTestDependencies
{
    public $translator;
}
