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
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    /** @var FormErrorResolver */
    private $formErrorResolver;

    /** @var PropertyAccessor */
    private $propertyAccessor;

    public function __construct(FormErrorResolver $formErrorResolver)
    {
        $this->formErrorResolver = $formErrorResolver;
        $this->propertyAccessor = new PropertyAccessor();
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('form_errors_recursive', [$this, 'getErrorRecursive']),
            new TwigFunction('form_has_errors', [$this, 'hasErrors']),
            new TwigFunction('form_is_submitted', [$this, 'isSubmitted']),
            new TwigFunction('form_is_successful', [$this, 'isSuccessful']),
            new TwigFunction('form_custom_name', [$this, 'getCustomName']),
            new TwigFunction('form_attr', [$this, 'getAttributes'], ['is_safe' => array('html')]),
        ];
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

    public function getCustomName($data, $customNameProperty)
    {
        if (is_string($customNameProperty)) {
            return $this->propertyAccessor->getValue($data, $customNameProperty);
        } elseif (is_callable($customNameProperty)) {
            return call_user_func($customNameProperty, $data);
        }
        return null;
    }

    public function getAttributes($data)
    {
        $return = '';
        foreach ($data->vars['attr'] as $key => $value) {
            $return .= htmlentities($key) . '="' . htmlentities($value) . '" ';
        }
        return trim($return);
    }
}
