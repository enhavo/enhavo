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
    public function getActions(array $configuration, ?object $resource = null, string|array|null $arrangement = null): array
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

        return $this->arrangeActions($actions, $arrangement);
    }

    public function createViewData(array $configuration, ?object $resource = null, string|array|null $arrangement = null): array
    {
        $data = [];
        $actions = $this->getActions($configuration, $resource, $arrangement);
        foreach ($actions as $action) {
            $data[] = $action->createViewData($resource);
        }

        return $data;
    }

    private function arrangeActions(array $actions, string|array|null $arrangement): array
    {
        $keys = $this->normalizeArrangement($arrangement);
        if (empty($keys)) {
            return $actions;
        }

        $arranged = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $actions)) {
                $arranged[$key] = $actions[$key];
                unset($actions[$key]);
            }
        }

        return $arranged + $actions;
    }

    /**
     * @return string[]
     */
    private function normalizeArrangement(string|array|null $arrangement): array
    {
        if (null === $arrangement) {
            return [];
        }

        if (is_string($arrangement)) {
            $arrangement = preg_split('/\s+/', trim($arrangement), -1, PREG_SPLIT_NO_EMPTY);
        }

        return array_values(array_unique(array_map('strval', $arrangement)));
    }
}
