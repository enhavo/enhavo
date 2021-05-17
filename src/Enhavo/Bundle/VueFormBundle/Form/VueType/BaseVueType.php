<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseVueType implements VueTypeInterface
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * FormVueType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getComponent(): ?string
    {
        return null;
    }

    public static function getBlocks(): array
    {
        return [
            'button' => 1,
            'form' => 1
        ];
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['disabled'] = $view->vars['disabled'];

        if ($view->vars['label'] && $view->vars['translation_domain'] !== false) {
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
}
