<?php
/**
 * Form.php
 *
 * @since 05/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Twig;

use Enhavo\Bundle\FormBundle\Error\FormErrorResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormErrorIterator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    /**
     * @var FormErrorResolver
     */
    private $formErrorResolver;
    
    public function __construct(FormErrorResolver $formErrorResolver)
    {
        $this->formErrorResolver = $formErrorResolver;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('form_errors_recursive', array($this, 'getErrorRecursive')),
            new TwigFunction('form_has_errors', array($this, 'hasErrors')),
            new TwigFunction('form_is_submitted', array($this, 'isSubmitted')),
            new TwigFunction('form_is_successful', array($this, 'isSuccessful')),
        );
    }

    public function getErrorRecursive(FormView $formView)
    {
        $form = $this->convertFormViewToForm($formView);
        if($form) {
            $errors = $this->formErrorResolver->getErrors($form);
            return $errors;
        }
        return [];
    }

    public function hasErrors(FormView $formView)
    {
        $form = $this->convertFormViewToForm($formView);
        if($form) {
            return $this->formErrorResolver->hasErrors($form);
        }
        return false;
    }

    public function isSubmitted(FormView $formView)
    {
        $form = $this->convertFormViewToForm($formView);
        if($form) {
            return $this->formErrorResolver->isSubmitted($form);
        }
        return false;
    }

    public function isSuccessful(FormView $formView)
    {
        $form = $this->convertFormViewToForm($formView);
        if($form) {
            return $this->formErrorResolver->isSuccessful($form);
        }
        return false;
    }

    /**
     * @param FormView $formView
     * @return null|FormInterface
     */
    private function convertFormViewToForm(FormView $formView)
    {
        $vars = $formView->vars;
        if(isset($vars['errors'])) {
            $errors = $formView->vars['errors'];
            if($errors instanceof FormErrorIterator) {
                $form = $errors->getForm();
                if($form instanceof FormInterface) {
                    return $form;
                }
            }
        }
        return null;
    }
}
