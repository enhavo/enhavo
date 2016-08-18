<?php

namespace Enhavo\Bundle\ShopBundle\Checkout;

use Sylius\Bundle\FlowBundle\Process\Builder\ProcessBuilderInterface;
use Sylius\Bundle\FlowBundle\Process\Scenario\ProcessScenarioInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CheckoutProcessScenario implements ProcessScenarioInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ProcessBuilderInterface $builder)
    {
        $builder
            ->add('addressing', 'enhavo_checkout_addressing')
            ->add('payment', 'enhavo_checkout_payment')
            ->add('confirm', 'enhavo_checkout_confirm')
        ;
    }
}
