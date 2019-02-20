<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadActionType extends AbstractActionType implements ActionTypeInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'download-action',
            'label' => 'label.download',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'delete'
        ]);
    }

    public function getType()
    {
        return 'download';
    }
}