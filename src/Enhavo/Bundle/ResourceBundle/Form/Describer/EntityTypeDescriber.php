<?php

namespace Enhavo\Bundle\ResourceBundle\Form\Describer;

use Enhavo\Bundle\ApiBundle\Documentation\Model\Schema;
use Enhavo\Bundle\ResourceBundle\Form\FormTypeDescriberInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormTypeInterface;

class EntityTypeDescriber implements FormTypeDescriberInterface
{
    public function describe($options, FormTypeInterface $form, Schema $schema)
    {
        if ($options['multiple']) {
            $schema->array()->items()->string()->description('Id');
        } else {
            $schema->string()->description('Id');
        }
    }

    public static function getFormTypes(): array
    {
        return [EntityType::class];
    }
}
