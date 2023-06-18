<?php

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChoiceVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['expanded'] = $view->vars['expanded'];
        $data['multiple'] = $view->vars['multiple'];
        $data['choices'] = $this->getChoices($view->vars['choices'], $view);
        $data['placeholder'] = $view->vars['placeholder'] ? $this->translator->trans($view->vars['placeholder'], [], $view->vars['translation_domain']) : null ;
        $data['placeholderInChoices'] = $view->vars['placeholder_in_choices'];
        $data['preferredChoices'] = $view->vars['preferred_choices'];
        $data['separator'] = $view->vars['separator'];
    }

    private function getChoices($choices, FormView $view): array
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

    public function finishVueData(FormView $view, VueData $data, array $options)
    {
        if ($options['expanded']) {
            foreach ($view as $childView) {
                /** @var VueData $vueData */
                $vueData = $childView->vars['vue_data'];
                $vueData->set('fullName', $childView->vars['full_name']);
                $vueData->set('value', $childView->vars['value']);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-choice',
            'component_model' => 'ChoiceForm'
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChoiceType::class];
    }
}
