<?php

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TypesType extends AbstractType
{
    public function __construct(
        private readonly array $types,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'page.label.type',
            'translation_domain' => 'EnhavoPageBundle',
            'choices' => $this->getChoices(),
            'placeholder' => '---'
        ]);
    }

    private function getChoices(): array
    {
        $choices = [];
        foreach ($this->types as $key => $type) {
            $label = $this->translator->trans($type['label'], [], $type['translation_domain']);
            $choices[$label] = $key;
        }
        return $choices;
    }
}
