<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChoiceVueType implements VueTypeInterface
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * ChoiceVueType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getComponent(): ?string
    {
        return 'form-choice';
    }

    public static function getBlocks(): array
    {
        return ['choice' => 1];
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['expanded'] = $view->vars['expanded'];
        $data['multiple'] = $view->vars['multiple'];
        $data['choices'] = $this->getChoices($view->vars['choices'], $view);
        $data['placeholder'] = $view->vars['placeholder'] ?? $this->translator->trans($view->vars['placeholder'], [], $view->vars['translation_domain']) ;
        $data['placeholderInChoices'] = $view->vars['placeholder_in_choices'];
        $data['preferredChoices'] = $view->vars['preferred_choices'];
        $data['separator'] = $view->vars['separator'];
    }

    private function getChoices($choices, FormView $view)
    {
        $data = [];
        /** @var ChoiceView $choice */
        foreach ($choices as $key => $choice) {
            if (\is_iterable($choice)) {
                $data[] = [
                    'label' => $this->translator->trans($choice->label, [], $view->vars['choice_translation_domain']),
                    'choices' => $this->getChoices($choice, $view)
                ];
            } else {
                $data[] = [
                    'label' => $this->translator->trans($choice->label, [], $view->vars['choice_translation_domain']),
                    'attr' => $choice->attr,
                    'value' => $choice->value,
                    'choices' => []
                ];
            }
        }
        return $data;
    }
}
