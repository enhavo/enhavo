<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.11.17
 */

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Action\ActionManager;
use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionType extends AbstractColumnType
{
    /**
     * @var ActionManager
     */
    private $actionManager;

    public function __construct(ActionManager $actionManager)
    {
        $this->actionManager = $actionManager;
    }

    public function createResourceViewData(array $options, $resource)
    {
        return $this->actionManager->createActionsViewData([$options['action']], $resource)[0];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-action',
        ]);
        $resolver->setRequired(['action']);
    }

    public function getType()
    {
        return 'action';
    }
}
