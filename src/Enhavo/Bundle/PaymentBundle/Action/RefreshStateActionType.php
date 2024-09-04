<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\Type\UrlActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RefreshStateActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'route' => 'sylius_payment_refresh_state',
            'component' => 'save-action',
            'icon' => 'refresh',
            'append_id' => true,
            'label' => 'payment.action.refresh_state',
            'translation_domain' => 'EnhavoPaymentBundle'
        ]);
    }

    public static function getParentType(): ?string
    {
        return UrlActionType::class;
    }

    public static function getName(): ?string
    {
        return 'payment_refresh_state';
    }
}
