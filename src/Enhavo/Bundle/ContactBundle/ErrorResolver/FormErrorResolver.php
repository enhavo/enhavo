<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 11.11.15
 * Time: 13:41
 */

namespace Enhavo\Bundle\ContactBundle\ErrorResolver;

use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormErrorResolver {
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
     * @return array
     */
    public function getErrors(FormInterface $form)
    {
        return $this->getErrorMessages($form);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getErrorArray(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getErrorMessages(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $this->translator->trans($error->getMessage());
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $childErrors = $this->getErrorMessages($child);
                foreach($childErrors as $childError) {
                    $errors[] = $childError;
                }
            }
        }

        return $errors;
    }
}