<?php

namespace Enhavo\Bundle\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SpecialPageType extends AbstractType
{
    /** @var array */
    private $specialPages;

    /** @var TranslatorInterface */
    private $translator;

    /**
     * SpecialPageType constructor.
     * @param array $specialPages
     * @param TranslatorInterface $translator
     */
    public function __construct(array $specialPages, TranslatorInterface $translator)
    {
        $this->specialPages = $specialPages;
        $this->translator = $translator;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => $this->getChoices(),
            'placeholder' => '---'
        ]);
    }

    private function getChoices()
    {
        $choices = [];
        foreach ($this->specialPages as $code => $specialPage) {
            $label = $this->translator->trans($specialPage['label'], [], $specialPage['translation_domain']);
            $choices[$label] = $code;
        }
        return $choices;
    }
}
