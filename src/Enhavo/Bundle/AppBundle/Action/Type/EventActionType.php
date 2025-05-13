<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author gseidel
 */
class EventActionType extends AbstractActionType implements ActionTypeInterface
{
    public function createViewData(array $options, Data $data, object $resource = null): void
    {
        $data['event'] = $options['event'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'EnhavoAppBundle',
            'model' => 'EventAction',
        ]);

        $resolver->setRequired(['event']);
    }

    public static function getName(): ?string
    {
        return 'event';
    }
}
