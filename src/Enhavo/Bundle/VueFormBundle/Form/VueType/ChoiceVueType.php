<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChoiceVueType extends AbstractVueType
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public static function supports(FormView $formView): bool
    {
        return in_array('choice', $formView->vars['block_prefixes']);
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
        $data['component'] = 'form-choice';
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
