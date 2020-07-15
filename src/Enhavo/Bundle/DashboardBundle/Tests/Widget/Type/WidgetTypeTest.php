<?php


namespace Enhavo\Bundle\DashboardBundle\Tests\Widget\Type;


use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\DashboardBundle\Widget\Type\WidgetType;
use Enhavo\Bundle\DashboardBundle\Widget\Widget;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WidgetTypeTest extends TestCase
{
    /**
     * @var WidgetType
     */
    private $instance;

    public function setUp()
    {
        $this->instance = $this->createInstance();
    }

    public function testPermission()
    {
        $options = [
            'permission' => 'ROLE_TEST'
        ];

        $this->assertEquals('ROLE_TEST', $this->instance->getPermission($options));
    }

    public function testHidden()
    {
        $options = [
            'hidden' => true
        ];

        $this->assertTrue($this->instance->isHidden($options));
    }

    public function testConfigureOptions()
    {
        $optionsResolver = new OptionsResolver();
        $this->instance->configureOptions($optionsResolver);

        $this->expectException(MissingOptionsException::class);
        $optionsResolver->resolve([]);
    }

    public function testCreateViewData()
    {
        $options = [
            'label' => 'label',
            'component' => 'component123',
            'translation_domain' => null
        ];

        $viewData = new ViewData();
        $this->instance->createViewData($options, $viewData);

        $this->assertEquals('translated', $viewData->get('label'));
        $this->assertEquals('component123', $viewData->get('component'));
    }

    private function createInstance()
    {
        return new WidgetType($this->createTranslatorMock('translated'));
    }

    /**
     * @return Widget|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTranslatorMock($willReturn = '')
    {
        /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('trans')->willReturn($willReturn);

        return $mock;
    }
}
