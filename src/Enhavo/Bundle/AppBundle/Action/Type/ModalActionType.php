<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data['modal'] = $options['modal'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => 'ModalAction',
        ]);

        $resolver->setRequired(['modal']);
    }

    public static function getName(): ?string
    {
        return 'modal';
    }
}
