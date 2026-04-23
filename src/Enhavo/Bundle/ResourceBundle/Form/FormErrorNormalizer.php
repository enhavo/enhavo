<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Symfony\Component\Form\FormInterface;

class FormErrorNormalizer implements FormNormalizerInterface
{
    public function normalize(FormInterface $form, array $options = []): array
    {
        $errors = [];
        $this->collectErrors($form, '', $errors);

        return $errors;
    }

    private function collectErrors(FormInterface $form, string $path, array &$errors): void
    {
        foreach ($form->getErrors() as $error) {
            if (!isset($errors[$path])) {
                $errors[$path] = [];
            }
            $errors[$path][] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            $childPath = $path === '' ? $child->getName() : $path . '.' . $child->getName();
            $this->collectErrors($child, $childPath, $errors);
        }
    }
}
