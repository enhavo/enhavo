<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $data['modal'] = $options['modal'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'modal-action',
        ]);

        $resolver->setRequired(['modal']);
    }

    public static function getName(): ?string
    {
        return 'modal';
    }
}
