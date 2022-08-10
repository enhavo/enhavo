<?php

namespace Enhavo\Bundle\ShopBundle\Form\Type\Checkout;

use Enhavo\Bundle\ShopBundle\Form\Type\AddressSubjectType;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options): void {
            $form = $event->getForm();
            $resource = $event->getData();

            Assert::isInstanceOf($resource, OrderInterface::class);

            /** @var UserInterface $user */
            $user = $resource->getUser();

            if (null === $user) {
                $form->add('email', TextType::class);
            }
        });
    }

    public function getParent()
    {
        return AddressSubjectType::class;
    }
}
