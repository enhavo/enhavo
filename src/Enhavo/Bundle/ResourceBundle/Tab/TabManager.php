<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
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
    )
    {
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

            if ($tab->getPermission($input) !== null && !$this->checker->isGranted($tab->getPermission())) {
                continue;
            }

            $tabs[$key] = $tab;
        }

        return $tabs;
    }
}
