<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Provider;


use Enhavo\Bundle\DashboardBundle\Provider\AbstractDashboardProviderType;
use Enhavo\Bundle\DashboardBundle\Provider\Provider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProviderTest extends TestCase
{
    public function testGetDataReturnsTypeGetData()
    {
        $typeMock = $this->createMock(AbstractDashboardProviderType::class);
        $typeMock->method('getData')->willReturn('DATA678');

        $provider = new Provider($typeMock, [], []);

        $this->assertEquals('DATA678', $provider->getData());
    }

    public function testGetDataUsesOptionsAsParameter()
    {
        $typeMock = $this->createMock(AbstractDashboardProviderType::class);
        $typeMock->method('getData')->willReturnCallback(
            function ($options) {
                return $options;
            }
        );

        $typeMock->method('configureOptions')->willReturnCallback(
            function (OptionsResolver $resolver) {
                $resolver->setDefaults([
                    'key' => 'value1'
                ]);
            }
        );

        $options = [
            'key' => 'value2'
        ];
        $provider = new Provider($typeMock, [], $options);

        $this->assertEquals($options, $provider->getData());
    }
}
