<?php

namespace Enhavo\Bundle\TranslationBundle\Action;

use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\Type\SaveActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslateActionType extends AbstractActionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->remove('route');
        $resolver->setRequired('route');

        $resolver->setDefaults([
            'icon' => 'translate',
            'label' => 'action.label.translate',
            'translation_domain' => 'EnhavoTranslationBundle',
        ]);
    }

    public static function getName(): ?string
    {
        return 'translate';
    }

    public static function getParentType(): ?string
    {
        return SaveActionType::class;
    }
}
