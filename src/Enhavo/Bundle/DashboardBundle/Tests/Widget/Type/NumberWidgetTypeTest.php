<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget\Type;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Provider\ProviderInterface;
use Enhavo\Bundle\DashboardBundle\Widget\Type\NumberWidgetType;
use Enhavo\Bundle\DashboardBundle\Widget\Widget;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberWidgetTypeTest extends TestCase
{
    public function testCreateViewData()
    {
        $numberWidgetType = new NumberWidgetType($this->createFactory(345));

        $viewData = new ViewData();
        $options = [
            'provider' => [
                'type' => 'type'
            ]
        ];
        $numberWidgetType->createViewData($options, $viewData);
        $this->assertEquals(345, $viewData->get('value'));
    }

    public function testGetName()
    {
        $numberWidgetType = new NumberWidgetType($this->createFactory());

        $this->assertEquals('number', $numberWidgetType->getName());
    }

    public function testConfigureOptionsMissingRequiredParameter()
    {
        $numberWidgetType = new NumberWidgetType($this->createFactory());

        $optionsResolver = new OptionsResolver();
        $numberWidgetType->configureOptions($optionsResolver);

        $this->expectException(MissingOptionsException::class);
        $optionsResolver->resolve([]);
    }

    private function createFactory($data = 0)
    {
        /**
         * @return Widget|\PHPUnit_Framework_MockObject_MockObject
         */
        /** @var \PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(FactoryInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('create')->willReturn($this->createProviderMock($data));

        return $mock;
    }

    /**
     * @return Widget|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createProviderMock($data = 0)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(ProviderInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('getData')->willReturn($data);
        return $mock;
    }
}
