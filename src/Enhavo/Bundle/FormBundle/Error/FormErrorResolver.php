<?php

namespace Enhavo\Bundle\FormBundle\Error;

use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author gseidel
 */
class FormErrorResolver
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormInterface $form
     * @return FormError[]
     */
    public function getErrors(FormInterface $form)
    {
        return $this->getFormErrors($form);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getFormErrors(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error;
        }

        foreach ($form->all() as $child) {
            if ($child->isSubmitted() && !$child->isValid()) {
                $childErrors = $this->getFormErrors($child);
                foreach($childErrors as $childError) {
                    $errors[] = $childError;
                }
            }
        }

        return $errors;
    }

    public function getErrorMessages(FormInterface $form)
    {
        $errors = $this->getFormErrors($form);
        $messages = [];

        /** @var FormError $error */
        foreach ($errors as $error) {
            $messages[$this->getSubFormName($error->getOrigin())] = $error->getMessage();
        }

        return $messages;
    }

    public function getErrorFieldNames($form)
    {
        $errors = $this->getErrors($form);

        $errorFields = [];
        if (count($errors) > 0) {
            foreach($errors as $error) {
                $errorFields[] = $this->getSubFormName($error->getOrigin());
            }
        }
        return $errorFields;
    }

    protected function getSubFormName(FormInterface $form)
    {
        if ($form->getParent() == null) {
            return $form->getName();
        } else {
            return $this->getSubFormName($form->getParent()) . '[' . $form->getName() . ']';
        }
    }

    /**
     * @param FormInterface $form
     * @return boolean
     */
    public function hasErrors(FormInterface $form)
    {
        return (boolean)count($this->getFormErrors($form));
    }

    /**
     * @param FormInterface $form
     * @return boolean
     */
    public function isSubmitted(FormInterface $form)
    {
        return $form->isSubmitted();
    }

    /**
     * @param FormInterface $form
     * @return boolean
     */
    public function isSuccessful(FormInterface $form)
    {
        return $this->isSubmitted($form) && !$this->hasErrors($form);
    }
}
