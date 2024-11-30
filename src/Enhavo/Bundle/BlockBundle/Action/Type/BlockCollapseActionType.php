<?php

namespace Enhavo\Bundle\BlockBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockCollapseActionType extends AbstractActionType
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data['property'] = $options['property'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'BlockCollapseAction',
            'component' => 'action-block-collapse',
            'label' => 'collapse',
            'icon' => '',
        ]);

        $resolver->setRequired('property');
    }

    public static function getName(): ?string
    {
        return 'block_collapse';
    }
}
