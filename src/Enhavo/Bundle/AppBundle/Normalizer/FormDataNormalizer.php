<?php

namespace Enhavo\Bundle\AppBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\DataNormalizerInterface;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;

class FormDataNormalizer implements DataNormalizerInterface
{
    public function __construct(
        private VueForm $vueForm
    )
    {
    }

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        $this->vueForm->createData($object instanceof Form ? $object->createView() : $object);
    }

    public static function getSupportedTypes(): array
    {
        return [FormView::class, Form::class];
    }
}
