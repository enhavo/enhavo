<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Enhavo\Bundle\VueFormBundle\Form\VueTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormVueType implements VueTypeInterface
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

    public function getComponent($options): string
    {
        return 'form-form';
    }

    public static function getFormTypes(): array
    {
        return [FormType::class => 1];
    }

    public function buildView(FormView $view, FormInterface $form, array $options, VueData $data)
    {
        $data['name'] = $form->getName();
        $data['value'] = $form->getViewData();
        $data['compound'] = $view->vars['compound'];
        $data['labelFormat'] = $view->vars['label_format'];
        $data['id'] = $view->vars['id'];
        $data['required'] = $view->vars['required'];
        $data['labelAttr'] = $view->vars['label_attr'];
        $data['attr'] = $view->vars['attr'];
        $data['disabled'] = $view->vars['disabled'];
        $data['required'] = $view->vars['required'];

        if ($view->vars['label'] && $view->vars['translation_domain'] !== false) {
            $data['label'] = $this->translator->trans($view->vars['label'], $view->vars['label_translation_parameters'], $view->vars['translation_domain']);
        } else {
            $data['label'] = $view->vars['label'];
        }

        if (isset($view->vars['attr']['placeholder']) && is_string($view->vars['attr']['placeholder'])) {
            $view->vars['attr']['placeholder'] = $this->translator->trans($view->vars['attr']['placeholder'], $view->vars['attr_translation_parameters'], $view->vars['translation_domain']);
        }

        if (isset($view->vars['attr']['title']) && is_string($view->vars['attr']['title'])) {
            $view->vars['attr']['title'] = $this->translator->trans($view->vars['attr']['title'], $view->vars['attr_translation_parameters'], $view->vars['translation_domain']);
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options, VueData $data)
    {
        $errors = [];
        foreach ($view->vars['errors'] as $error) {
            $errors[] = [
                'message' => $error->getMessage()
            ];
        }
        $data['errors'] = $errors;
    }
}
