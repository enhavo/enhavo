<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;

class ChainRolesProvider implements RolesProvider
{
    /**
     * @var RolesProvider[]
     */
    private $rolesProviders = [];

    public function addProvider(RolesProvider $rolesProvider)
    {
        $this->rolesProviders[] = $rolesProvider;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles = [];
        foreach ($this->rolesProviders as $rolesProvider) {
            $roles = array_merge($roles, $rolesProvider->getRoles());
        }

        return $roles;
    }
}
