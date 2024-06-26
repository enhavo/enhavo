<?php

namespace Enhavo\Bundle\ResourceBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropdownActionType extends AbstractActionType
{
    public function __construct(
        private readonly ActionManager $actionManager
    )
    {
    }

    public function createViewData(array $options, Data $data, ResourceInterface $resource = null): void
    {
        $actions = $this->actionManager->getActions($options['items'], $resource);
        $items = [];
        foreach($actions as $action) {
            $items[] = $action->createViewData($resource);
        }

        $data->set('items', $items);
        $data->set('closeAfter', $options['close_after']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'dropdown-action',
            'close_after' => true,
        ]);

        $resolver->setRequired([
            'items'
        ]);
    }

    public static function getName(): ?string
    {
        return 'dropdown';
    }
}
