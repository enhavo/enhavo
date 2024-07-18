<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\AppBundle\Action\Type\OpenActionType;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class PayActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data->set('url', $this->getUrl($options, $resource));
    }

    private function getUrl(array $options, $resource = null): string
    {
        $parameters = [
            'tokenValue' => $resource->getToken()
        ];

        $parameters = array_merge_recursive($parameters, $options['route_parameters']);

        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'payment.action.pay',
            'translation_domain' => 'EnhavoPaymentBundle',
            'icon' => 'payment',
            'target' => '_blank',
            'condition' => 'resource.getState() in ["new", "processing", "authorized"]',
            'route' => 'sylius_payment_authorize',
        ]);
    }

    public static function getParentType(): ?string
    {
        return OpenActionType::class;
    }

    public static function getName(): ?string
    {
        return 'payment_pay';
    }
}
