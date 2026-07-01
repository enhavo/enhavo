<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\FormInterface;

class VueFormNormalizer implements FormNormalizerInterface
{
    public function __construct(
        private readonly VueForm $vueForm,
    )
    {
    }

    public function normalize(FormInterface $form, array $options = []): array
    {
        $fields = $options['fields'] ?? null;
        return $this->vueForm->createData($form->createView(), $fields);
    }
}
