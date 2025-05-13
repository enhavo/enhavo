<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Error;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @return FormError[]
     */
    public function getErrors(FormInterface $form)
    {
        return $this->getFormErrors($form);
    }

    /**
     * @return array
     */
    protected function getFormErrors(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error;
        }

        foreach ($form->all() as $child) {
            if ($child->isSubmitted() && !$child->isValid()) {
                $childErrors = $this->getFormErrors($child);
                foreach ($childErrors as $childError) {
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
            foreach ($errors as $error) {
                $errorFields[] = $this->getSubFormName($error->getOrigin());
            }
        }

        return $errorFields;
    }

    protected function getSubFormName(FormInterface $form)
    {
        if (null == $form->getParent()) {
            return $form->getName();
        }

        return $this->getSubFormName($form->getParent()).'['.$form->getName().']';
    }

    /**
     * @return bool
     */
    public function hasErrors(FormInterface $form)
    {
        return (bool) count($this->getFormErrors($form));
    }

    /**
     * @return bool
     */
    public function isSubmitted(FormInterface $form)
    {
        return $form->isSubmitted();
    }

    /**
     * @return bool
     */
    public function isSuccessful(FormInterface $form)
    {
        return $this->isSubmitted($form) && !$this->hasErrors($form);
    }
}
