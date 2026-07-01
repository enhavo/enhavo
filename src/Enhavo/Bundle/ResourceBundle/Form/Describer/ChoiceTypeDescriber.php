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

        if (is_array($choices)) {
            foreach (array_values($choices) as $choice) {
                if ($choice === null) {
                    $this->choicesAsNumbers($options, $schema);
                    return;
                }
            }
        }

        $schema->string()
            ->enum(is_array($choices) ? array_values($choices) : [])
            ->nullable(!!$options['placeholder']);
    }

    public function choicesAsNumbers($options, Schema $schema): void
    {
        if (is_array($options['choices'])) {
            foreach ($options['choices'] as $label => $value) {
                $choices[] = $label;
            }
        }

        $description = [];
        foreach ($choices as $value => $label) {
            $description[] = sprintf("'%s' => %s", $value, $label);
        }

        $schema->string()
            ->enum(array_keys($choices))
            ->description(implode(", ", $description));
    }

    public static function getFormTypes(): array
    {
        return [ChoiceType::class];
    }
}
