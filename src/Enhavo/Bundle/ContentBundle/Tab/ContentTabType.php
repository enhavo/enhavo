<?php

namespace Enhavo\Bundle\ContentBundle\Tab;

use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Enhavo\Bundle\ResourceBundle\Tab\Type\FormTabType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentTabType extends AbstractTabType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
       $resolver->setDefaults([
           'label' => 'content.label.content',
           'translation_domain' =>  'EnhavoContentBundle',
           'arrangement' => [
               'content',
           ]
       ]);
    }

    public static function getParentType(): ?string
    {
        return FormTabType::class;
    }

    public static function getName(): ?string
    {
        return 'content';
    }
}
