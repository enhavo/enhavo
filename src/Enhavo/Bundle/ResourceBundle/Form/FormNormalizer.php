<?php

namespace Enhavo\Bundle\ResourceBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FormNormalizer implements FormNormalizerInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
    )
    {
    }

    public function normalize(FormInterface $form, array $options = []): array
    {
        return $this->normalizer->normalize($form, null, $options);
    }
}
