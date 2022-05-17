<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyChoiceType extends AbstractType
{
    public function __construct(
        private array $currencies,
    )
    {}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->getChoices(),
            'placeholder' => count($this->currencies) == 1 ? null : '---',
        ]);
    }

    private function getChoices()
    {
        $choices = [];
        foreach ($this->currencies as $currency)
        {
            $choices[$currency] = $currency;
        }
        return $choices;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
