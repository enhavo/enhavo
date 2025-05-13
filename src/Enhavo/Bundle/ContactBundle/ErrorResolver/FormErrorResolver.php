<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContactBundle\ErrorResolver;

use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @return array
     */
    public function getErrors(FormInterface $form)
    {
        return $this->getErrorMessages($form);
    }

    /**
     * @return array
     */
    protected function getErrorArray(FormInterface $form)
    {
        $errors = [];

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
     * @return array
     */
    protected function getErrorMessages(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $this->translator->trans($error->getMessage());
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $childErrors = $this->getErrorMessages($child);
                foreach ($childErrors as $childError) {
                    $errors[] = $childError;
                }
            }
        }

        return $errors;
    }
}
