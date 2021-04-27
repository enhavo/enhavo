<?php

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class DropdownActionType extends AbstractActionType implements ActionTypeInterface
{
    /** @var ActionManager */
    private $actionManager;

    /**
     * DropdownActionType constructor.
     * @param TranslatorInterface $translator
     * @param ActionManager $actionManager
     */
    public function __construct(TranslatorInterface $translator, ActionManager $actionManager)
    {
        parent::__construct($translator);
        $this->actionManager = $actionManager;
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $actions = $this->actionManager->getActions($options['items'], $resource);
        $items = [];
        foreach($actions as $action) {
            $items[] = $action->createViewData($resource);
        }

        $data = array_merge($data, [
            'items' => $items,
            'closeListOnItemClick' => $options['close_list_on_item_click']
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'dropdown-action',
            'close_list_on_item_click' => true,
        ]);

        $resolver->setRequired([
            'items'
        ]);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'dropdown';
    }
}
