<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Symfony\Component\Form\FormInterface;

interface FormNormalizerInterface
{
    public function normalize(FormInterface $form, array $options = []): array;
}
