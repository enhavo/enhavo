<?php

namespace Enhavo\Bundle\AppBundle\Normalizer;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Normalizer\AbstractDataNormalizer;
use Enhavo\Bundle\VueFormBundle\Form\VueForm;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;

class FormDataNormalizer extends AbstractDataNormalizer
{
    public function __construct(
        private VueForm $vueForm,
    )
    {
    }

    public function buildData(Data $data, $object, string $format = null, array $context = [])
    {
        $properties = $this->vueForm->createData($object instanceof Form ? $object->createView() : $object);
        foreach ($properties as $key => $value) {
            $data->set($key, $value);
        }
    }

    public static function getSupportedTypes(): array
    {
        return [FormView::class, Form::class];
    }
}
