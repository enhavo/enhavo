<?php

namespace Enhavo\Bundle\PaymentBundle\Action;

use Enhavo\Bundle\AccountingBundle\Model\Accounting;
use Enhavo\Bundle\AppBundle\Action\AbstractUrlActionType;
use Enhavo\Bundle\AppBundle\Action\ActionLanguageExpression;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RefreshStateActionType extends AbstractUrlActionType
{
    /** @var RequestStack */
    private $requestStack;

    /**
     * CalculateAction constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(TranslatorInterface $translator, ActionLanguageExpression $actionLanguageExpression, RouterInterface $router, RequestStack $requestStack)
    {
        parent::__construct($translator, $actionLanguageExpression, $router);
        $this->requestStack = $requestStack;
    }

    protected function getUrl(array $options, $resource = null)
    {
        $parameters = [];

        if($options['append_id'] && $resource !== null && $resource->getId() !== null) {
            $parameters[$options['append_key']] = $resource->getId();
        }

        $parameters['view_id'] = $this->requestStack->getMainRequest()->get('view_id');

        $parameters = array_merge_recursive($parameters, $options['route_parameters']);

        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'route' => 'sylius_payment_refresh_state',
            'component' => 'save-action',
            'icon' => 'refresh',
            'append_id' => true,
            'label' => 'payment.action.refresh_state',
            'translation_domain' => 'EnhavoPaymentBundle'
        ]);
    }

    public function getType()
    {
        return 'payment_refresh_state';
    }
}
