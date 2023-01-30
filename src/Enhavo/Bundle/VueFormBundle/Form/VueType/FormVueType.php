<?php

namespace Enhavo\Bundle\VueFormBundle\Form\VueType;

use Enhavo\Bundle\VueFormBundle\Form\AbstractVueType;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormVueType extends AbstractVueType
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public static function supports(FormView $formView): bool
    {
        return in_array('form', $formView->vars['block_prefixes']);
    }

    public function buildView(FormView $view, VueData $data)
    {
        $data['name'] = $view->vars['name'];
        $data['value'] = $view->vars['value'];
        $data['compound'] = $view->vars['compound'];
        $data['id'] = $view->vars['id'];
        $data['required'] = $view->vars['required'];
        $data['fullName'] = $view->vars['full_name'];
        $data['size'] = $view->vars['size'];
        $data['help'] = $view->vars['help'];
        $data['helpAttr'] = $view->vars['help_attr'];
        $data['helpHtml'] = $view->vars['help_html'];
        $data['rowComponent'] = 'form-row';
        $data['component'] = null;
        if (!isset($data['componentModel'])) {
            $data['componentModel'] = null;
        }
        
        $errors = [];
        foreach ($view->vars['errors'] as $error) {
            $errors[] = [
                'message' => $error->getMessage()
            ];
        }
        $data['errors'] = $errors;
    }
}
