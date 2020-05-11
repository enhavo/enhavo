<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ActionManager
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * ActionManager constructor.
     * @param TypeCollector $collector
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(TypeCollector $collector, AuthorizationCheckerInterface $checker)
    {
        $this->collector = $collector;
        $this->checker = $checker;
    }

    public function createActionsViewData(array $configuration, $resource = null)
    {
        $data = [];
        $actions = $this->getActions($configuration, $resource);
        foreach($actions as $action) {
            $data[] = $action->createViewData($resource);
        }
        return $data;
    }

    public function getActions(array $configuration, $resource = null)
    {
        $actions = [];
        foreach($configuration as $name => $options) {
            $action = $this->createAction($options);

            if($action->isHidden($resource)) {
                continue;
            }

            if($action->getPermission($resource) !== null && !$this->checker->isGranted($action->getPermission())) {
                continue;
            }

            $actions[$name] = $action;
        }

        return $actions;
    }

    /**
     * @param $options
     * @return Action
     * @throws TypeMissingException
     */
    private function createAction($options)
    {
        if(!isset($options['type'])) {
            throw new TypeMissingException(sprintf('No type was given to create "%s"', Action::class));
        }

        /** @var ActionTypeInterface $type */
        $type = $this->collector->getType($options['type']);
        unset($options['type']);
        $action = new Action($type, $options);
        return $action;
    }
}
