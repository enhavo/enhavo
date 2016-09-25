<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Enhavo\Bundle\ShopBundle\Mock\EventSubscriberMock;
use Enhavo\Bundle\ShopBundle\Order\OrderAddressProvider;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Symfony\Component\Form\Test\TypeTestCase;
use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Symfony\Component\Form\PreloadedExtension;
use Enhavo\Bundle\ShopBundle\Entity\Order;
use Enhavo\Bundle\ShopBundle\Form\Type\OrderAddressType;

class OrderAddressTypeTest extends TypeTestCase
{
    public function testSubmitWithDifferentBillingAddress()
    {
        $orderAddressProvider = $this->getMockBuilder(OrderAddressProvider::class)->disableOriginalConstructor()->getMock();
        $orderAddressProvider->method('provide')->willReturn(null);
        $order = new Order();
        $formType = new OrderAddressType('Enhavo\Bundle\ShopBundle\Entity\Order', $orderAddressProvider);
        $form = $this->factory->create($formType, $order);
        $form->submit([
            'shippingAddress' => [
                'firstName' => 'ShippingFirstName',
                'lastName' => 'ShippingLastName'
            ],
            'billingAddress' => [
                'firstName' => 'BillingFirstName',
                'lastName' => 'BillingLastName'
            ],
            'differentBillingAddress' => '1'
        ]);

        $this->assertTrue($form->isValid());
        $this->assertEquals('ShippingFirstName', $order->getShippingAddress()->getFirstName());
        $this->assertEquals('ShippingLastName', $order->getShippingAddress()->getLastName());
        $this->assertEquals('BillingFirstName', $order->getBillingAddress()->getFirstName());
        $this->assertEquals('BillingLastName', $order->getBillingAddress()->getLastName());
    }

    public function testSubmitWithSameBillingAddress()
    {
        $orderAddressProvider = $this->getMockBuilder(OrderAddressProvider::class)->disableOriginalConstructor()->getMock();
        $orderAddressProvider->method('provide')->willReturn(null);
        $order = new Order();
        $formType = new OrderAddressType('Enhavo\Bundle\ShopBundle\Entity\Order', $orderAddressProvider);
        $form = $this->factory->create($formType, $order);
        $form->submit([
            'shippingAddress' => [
                'firstName' => 'ShippingFirstName',
                'lastName' => 'ShippingLastName'
            ],
            'billingAddress' => [
                'firstName' => 'BillingFirstName',
                'lastName' => 'BillingLastName'
            ]
        ]);

        $this->assertTrue($form->isValid());
        $this->assertEquals('ShippingFirstName', $order->getShippingAddress()->getFirstName());
        $this->assertEquals('ShippingLastName', $order->getShippingAddress()->getLastName());
        $this->assertEquals('ShippingFirstName', $order->getBillingAddress()->getFirstName());
        $this->assertEquals('ShippingLastName', $order->getBillingAddress()->getLastName());
    }

    protected function getExtensions()
    {
        $eventSubscriber = new EventSubscriberMock();
        $countryRepository = $this->getMockBuilder('Sylius\Component\Resource\Repository\RepositoryInterface')->getMock();
        $countryRepository->method('findBy')->willReturn([]);

        $types = [
            new AddressType('Sylius\Component\Addressing\Model\Address', [], $eventSubscriber),
            new CountryCodeChoiceType($countryRepository)
        ];

        return [
            new PreloadedExtension($types, [])
        ];
    }
}