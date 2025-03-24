<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Enhavo\Bundle\ResourceBundle\Action\ActionManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DropdownActionType extends AbstractActionType
{
    private ActionManager $actionManager;

    public function setActionManager(ActionManager $actionManager): void
    {
        $this->actionManager = $actionManager;
    }

    public function createViewData(array $options, Data $data, object $resource = null): void
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
            'close_after' => true,
            'model' => 'DropdownAction',
            'component' => 'action-dropdown',
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
