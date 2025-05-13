<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SpecialsType extends AbstractType
{
    public function __construct(
        private readonly array $specials,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'page.label.special_page',
            'translation_domain' => 'EnhavoPageBundle',
            'choices' => $this->getChoices(),
            'placeholder' => '---',
        ]);
    }

    private function getChoices(): array
    {
        $choices = [];
        foreach ($this->specials as $key => $special) {
            $label = $this->translator->trans($special['label'], [], $special['translation_domain']);
            $choices[$label] = $key;
        }

        return $choices;
    }
}
