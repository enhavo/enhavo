<?php

namespace Enhavo\Bundle\ShopBundle\Tests\Table\Widget;

use Enhavo\Bundle\ShopBundle\Entity\Order;
use Enhavo\Bundle\ShopBundle\Table\Widget\AddressWidget;
use Sylius\Component\Addressing\Model\Address;

class AddressWidgetTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $widget = new AddressWidget();
        $this->assertInstanceOf('Enhavo\Bundle\ShopBundle\Table\Widget\AddressWidget', $widget);
    }

    public function testRender()
    {
        $address = new Address();
        $address->setFirstName('FirstName');
        $address->setLastName('LastName');

        $order = new Order();
        $order->setBillingAddress($address);

        $widget = new AddressWidget();
        $value = $widget->render(['property' => 'billingAddress'], $order);
        $this->assertEquals('FirstName LastName', $value);
    }

    public function testType()
    {
        $widget = new AddressWidget();
        $this->assertEquals('shop_address', $widget->getType());
    }
}