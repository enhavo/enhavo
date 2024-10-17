<?php

namespace Enhavo\Bundle\BlockBundle\Tab;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Enhavo\Bundle\ResourceBundle\Tab\Type\FormTabType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockTabType extends AbstractTabType
{
    public function createViewData(array $options, Data $data, InputInterface $input = null): void
    {
        $data['property'] = $options['property'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
       $resolver->setDefaults([
           'label' => 'content.label.content',
           'translation_domain' =>  'EnhavoContentBundle',
           'component' => 'tab-block',
           'model' => 'BlockTab',
       ]);

        $resolver->setRequired(['property']);
    }

    public static function getParentType(): ?string
    {
        return FormTabType::class;
    }

    public static function getName(): ?string
    {
        return 'block';
    }
}
