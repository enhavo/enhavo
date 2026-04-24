<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Symfony\Component\Form\FormInterface;

class FormViewDataNormalizer implements FormNormalizerInterface
{
    public function normalize(FormInterface $form, array $options = []): array
    {
        return $this->normalizeForm($form);
    }

    private function normalizeForm(FormInterface $form)
    {
        if (!$form->all()) {
            return $form->getViewData();
        }
        $data = null;
        foreach ($form->all() as $child) {
            $name = $child->getConfig()->getName();
            $data[$name] = $this->normalizeForm($child);
        }
        return $data;
    }
}
