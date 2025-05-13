<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Extension;

use Enhavo\Bundle\FormBundle\Form\Helper\EntityTreeChoice;
use Enhavo\Bundle\FormBundle\Form\Type\EntityTreeType;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EntityTreeTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function finishVueData(FormView $view, VueData $data, array $options): void
    {
        $data['choices'] = $view->vars['choices'];

        if ($options['expanded']) {
            $tree = $this->normalizeChoices($view->vars['choice_tree_builder']->getChoices());
            $data['tree'] = $tree;
        } else {
            $data['tree'] = null;
            $data['choices'] = $this->getSelectChoices($view->vars['choices'], $view);
        }
    }

    /** @param EntityTreeChoice[] $choices */
    private function normalizeChoices(array $choices): array
    {
        $data = [];
        foreach ($choices as $choice) {
            $data[] = [
                'name' => $choice->getFormView()->vars['name'],
                'children' => $this->normalizeChoices($choice->getChildren()),
            ];
        }

        return $data;
    }

    private function getSelectChoices($choices, FormView $view): array
    {
        $data = [];
        /** @var ChoiceView $choice */
        foreach ($choices as $key => $choice) {
            if (\is_iterable($choice)) {
                $data[] = [
                    'label' => $this->translator->trans($choice->label, [], $view->vars['choice_translation_domain']),
                    'choices' => $this->getSelectChoices($choice, $view),
                ];
            } else {
                $data[] = [
                    'label' => $this->translator->trans($choice->label, [], $view->vars['choice_translation_domain']),
                    'attr' => $choice->attr,
                    'value' => $choice->value,
                    'choices' => [],
                ];
            }
        }

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-choice-tree',
            'model' => 'ChoiceTreeForm',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [EntityTreeType::class];
    }
}
