<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tab;

use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TabManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $tabFactory,
    ) {
    }

    /** @return Tab[] */
    public function getTabs(array $configuration, InputInterface $input): array
    {
        $tabs = [];
        foreach ($configuration as $key => $options) {
            /** @var Tab $tab */
            $tab = $this->tabFactory->create($options, $key);

            if (!$tab->isEnabled($input)) {
                continue;
            }

            if (null !== $tab->getPermission($input) && !$this->checker->isGranted($tab->getPermission($input))) {
                continue;
            }

            $tabs[$key] = $tab;
        }

        return $tabs;
    }
}
