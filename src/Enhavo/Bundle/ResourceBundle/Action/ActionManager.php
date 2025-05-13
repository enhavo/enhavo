<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Action;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ActionManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $actionFactory,
    ) {
    }

    /**
     * @return Action[]
     */
    public function getActions(array $configuration, ?object $resource = null): array
    {
        $actions = [];
        foreach ($configuration as $key => $options) {
            /** @var Action $action */
            $action = $this->actionFactory->create($options, $key);

            if (!$action->isEnabled($resource)) {
                continue;
            }

            if (null !== $action->getPermission($resource) && !$this->checker->isGranted($action->getPermission($resource))) {
                continue;
            }

            $actions[$key] = $action;
        }

        return $actions;
    }

    public function createViewData(array $configuration, ?object $resource = null): array
    {
        $data = [];
        $actions = $this->getActions($configuration, $resource);
        foreach ($actions as $action) {
            $data[] = $action->createViewData($resource);
        }

        return $data;
    }
}
