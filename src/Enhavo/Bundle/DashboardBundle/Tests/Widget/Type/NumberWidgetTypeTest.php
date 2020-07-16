<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget\Type;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Provider\Provider;
use Enhavo\Bundle\DashboardBundle\Widget\Type\NumberWidgetType;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberWidgetTypeTest extends TestCase
{
    public function testCreateViewData()
    {
        $dependencies = $this->createDependencies();

        $providerMock = $this->createMock(Provider::class);
        $providerMock->method('getData')->willReturn(543);
        $dependencies->factory->method('create')->willReturn($providerMock);

        $instance = $this->createInstance($dependencies);

        $viewData = new ViewData();
        $options = [
            'provider' => [
                'type' => 'type'
            ]
        ];
        $instance->createViewData($options, $viewData);
        $this->assertEquals(543, $viewData->get('value'));
    }

    public function testGetName()
    {
        $this->assertEquals('number', NumberWidgetType::getName());
    }

    public function testConfigureOptionsMissingRequiredParameter()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $optionsResolver = new OptionsResolver();
        $instance->configureOptions($optionsResolver);

        $this->expectException(MissingOptionsException::class);
        $optionsResolver->resolve([]);
    }

    private function createDependencies()
    {
        $dependencies = new NumberWidgetTypeTestDependencies();
        $dependencies->factory = $this->createMock(FactoryInterface::class);

        return $dependencies;
    }

    private function createInstance(NumberWidgetTypeTestDependencies $dependencies)
    {
        return new NumberWidgetType($dependencies->factory);
    }
}

class NumberWidgetTypeTestDependencies
{
    /** @var FactoryInterface|\PHPUnit_Framework_MockObject_MockObject $mock */
    public $factory;
}
