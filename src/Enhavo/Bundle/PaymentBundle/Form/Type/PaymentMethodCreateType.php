<?php

namespace Enhavo\Bundle\PaymentBundle\Form\Type;

use Enhavo\Bundle\PaymentBundle\Provider\PaymentMethodTypeProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentMethodCreateType extends AbstractType
{
    public function __construct(
        private PaymentMethodTypeProvider $provider,
        private TranslatorInterface $translator
    )
    {}

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder' => '---',
            'constraints' => [
                new NotBlank(),
            ],
            'choices' => $this->getChoices(),
            'label' => 'Method type'
        ]);
    }

    private function getChoices(): array
    {
        $choices = [];
        foreach ($this->provider->provide() as $type) {
            $choices[$this->translator->trans($type->getLabel(), [], $type->getTranslationDomain())] = $type->getType();
        }
        return $choices;
    }
}
