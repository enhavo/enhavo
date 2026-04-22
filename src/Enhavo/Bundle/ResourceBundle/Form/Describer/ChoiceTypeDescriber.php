<?php

namespace Enhavo\Bundle\ResourceBundle\Form\Describer;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Enhavo\Bundle\ResourceBundle\Form\FormTypeDescriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormTypeInterface;

class ChoiceTypeDescriber implements FormTypeDescriberInterface
{
    public function describe($options, FormTypeInterface $form, Schema $schema)
    {
        $choices = $options['choices'];
        $schema->string()
            ->enum(is_array($choices) ? array_values($choices) : []);
    }

    public static function getFormTypes(): array
    {
        return [ChoiceType::class];
    }
}
