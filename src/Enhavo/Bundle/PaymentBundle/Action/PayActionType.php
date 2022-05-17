<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayActionType extends OpenActionType
{
    protected function getUrl(array $options, $resource = null)
    {
        $parameters = [
            'tokenValue' => $resource->getToken()
        ];

        $parameters = array_merge_recursive($parameters, $options['route_parameters']);

        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'label' => 'payment.action.pay',
            'translation_domain' => 'EnhavoPaymentBundle',
            'icon' => 'payment',
            'target' => '_blank',
            'condition' => 'resource.getState() in ["new", "processing", "authorized"]',
            'route' => 'sylius_payment_authorize',
        ]);
    }

    public function getType()
    {
        return 'payment_pay';
    }
}
