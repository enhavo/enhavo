<?php

namespace Enhavo\Bundle\ShopBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Extension\ConditionTypeExtension;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Enhavo\Bundle\ShopBundle\Entity\AddressSubjectTrait;
use Enhavo\Bundle\ShopBundle\Form\Type\AddressSubjectType;
use Enhavo\Bundle\ShopBundle\Model\AddressSubjectInterface;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Sylius\Component\Addressing\Comparator\AddressComparator;
use Sylius\Component\Addressing\Model\Address;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressSubjectTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $translator->method('trans')->willReturnCallback(function($value) {
            return $value;
        });

        $addressComparator = new AddressComparator();
        $addressSubjectType = new AddressSubjectType($addressComparator, $translator);

        $eventSubscriber = new SubscriberMock();
        $addressType = new AddressType(Address::class, [], $eventSubscriber);

        $repository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $repository->method('findBy')->willReturn([]);

        $countryChoiceType = new CountryChoiceType($repository);
        $countryCodeChoiceType = new CountryCodeChoiceType($repository);

        $booleanType = new BooleanType($translator);

        return array(
            new PreloadedExtension(array($addressSubjectType), array()),
            new PreloadedExtension(array($addressType), array()),
            new PreloadedExtension(array($countryChoiceType), array()),
            new PreloadedExtension(array($countryCodeChoiceType), array()),
            new PreloadedExtension(array($booleanType), array()),
        );
    }

    protected function getTypeExtensions()
    {
        return [
            new ConditionTypeExtension()
        ];
    }


    public function testSubmitWithAllData()
    {
        $form = $this->factory->create(AddressSubjectType::class, null, [
            'data_class' => AddressSubjectMock::class
        ]);

        $form->submit([
            'billingAddress' => [
                'firstName' => 'Joe'
            ],
            'sameAddress' => '0',
            'shippingAddress' => [
                'firstName' => 'James'
            ],
        ]);

        /** @var AddressSubjectInterface $data */
        $data = $form->getData();
        $this->assertInstanceOf(AddressSubjectMock::class, $data);
        $this->assertInstanceOf(AddressInterface::class, $data->getBillingAddress());
        $this->assertInstanceOf(AddressInterface::class, $data->getShippingAddress());
        $this->assertEquals('Joe', $data->getBillingAddress()->getFirstName());
        $this->assertEquals('James', $data->getShippingAddress()->getFirstName());
    }

    public function testSubmitWithOnlyBillingData()
    {
        $form = $this->factory->create(AddressSubjectType::class, null, [
            'data_class' => AddressSubjectMock::class
        ]);

        $form->submit([
            'billingAddress' => [
                'firstName' => 'Joe'
            ],
            'sameAddress' => '1',
            'shippingAddress' => [
                'firstName' => 'James'
            ],
        ]);

        /** @var AddressSubjectInterface $data */
        $data = $form->getData();
        $this->assertEquals('Joe', $data->getBillingAddress()->getFirstName());
        $this->assertEquals('Joe', $data->getShippingAddress()->getFirstName());
    }

    public function testSubmitWithSubjectOption()
    {
        $mock = new AddressSubjectMock();
        $form = $this->factory->create(AddressSubjectType::class, null, [
            'subject' => $mock,
            'data_class' => AddressSubjectMock::class
        ]);

        $form->submit([
            'billingAddress' => [
                'firstName' => 'Joe'
            ],
            'sameAddress' => '1',
            'shippingAddress' => [
                'firstName' => 'James'
            ],
        ]);

        /** @var AddressSubjectInterface $data */
        $data = $form->getData();
        $this->assertEquals('Joe', $data->getBillingAddress()->getFirstName());
        $this->assertEquals('Joe', $data->getShippingAddress()->getFirstName());
    }

    public function testShippableFalseOption()
    {
        $form = $this->factory->create(AddressSubjectType::class, null, [
            'data_class' => AddressSubjectMock::class,
            'shippable' => false
        ]);

        $form->submit([
            'billingAddress' => [
                'firstName' => 'Joe'
            ],
        ]);

        /** @var AddressSubjectInterface $data */
        $data = $form->getData();
        $this->assertEquals('Joe', $data->getBillingAddress()->getFirstName());
        $this->assertNull($data->getShippingAddress());
    }
}

class AddressSubjectMock implements AddressSubjectInterface
{
    use AddressSubjectTrait;
}

class SubscriberMock implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [];
    }
}
