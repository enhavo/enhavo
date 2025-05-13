<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\VueFormBundle\Form\Extension;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['disabled'] = $view->vars['disabled'];

        if ($view->vars['label'] && false !== $view->vars['translation_domain']) {
            $data['label'] = $this->translator->trans($view->vars['label'], $view->vars['label_translation_parameters'], $view->vars['translation_domain']);
        } else {
            $data['label'] = $view->vars['label'];
        }

        $data['labelFormat'] = $view->vars['label_format'];
        $data['rowAttr'] = $view->vars['row_attr'];

        if (isset($view->vars['attr']['placeholder']) && is_string($view->vars['attr']['placeholder'])) {
            $view->vars['attr']['placeholder'] = $this->translator->trans($view->vars['attr']['placeholder'], $view->vars['attr_translation_parameters'], $view->vars['translation_domain']);
        }

        if (isset($view->vars['attr']['title']) && is_string($view->vars['attr']['title'])) {
            $view->vars['attr']['title'] = $this->translator->trans($view->vars['attr']['title'], $view->vars['attr_translation_parameters'], $view->vars['translation_domain']);
        }

        $data['attr'] = $view->vars['attr'];
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class, ButtonType::class];
    }
}
