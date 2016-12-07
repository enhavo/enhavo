<?php
/**
 * Form.php
 *
 * @since 05/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\Form\Error\FormErrorResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormErrorIterator;

class Form extends \Twig_Extension
{
    /**
     * @var FormErrorResolver
     */
    private $formErrorResolver;
    
    public function __construct(FormErrorResolver $formErrorResolver)
    {
        $this->formErrorResolver = $formErrorResolver;
    }

    public function getName()
    {
        return 'enhavo_form'; // prevent name conflict with symfony
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_errors_recursive', array($this, 'getErrorRecursive')),
            new \Twig_SimpleFunction('form_has_errors', array($this, 'hasErrors')),
            new \Twig_SimpleFunction('form_is_submitted', array($this, 'isSubmitted')),
            new \Twig_SimpleFunction('form_is_successful', array($this, 'isSuccessful')),
        );
    }

    public function getErrorRecursive(FormView $formView, $translationDomain = null)
    {
        $form = $this->convertFormViewToForm($formView);
        if($form) {
            return $this->formErrorResolver->getErrors($form, $translationDomain);
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