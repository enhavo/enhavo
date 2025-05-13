<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Twig;

use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/*
 * Gets the update route for a given resource
 */
class UpdateRoute extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('updateRoute', [$this, 'getUpdateRoute']),
        ];
    }

    public function getUpdateRoute($resource)
    {
        $roleUtil = new RoleUtil();
        $roleName = $roleUtil->getRoleName($resource, 'update');
        $updateRoute = str_replace('ROLE_', '', $roleName);

        return strtolower($updateRoute);
    }

    public function getName()
    {
        return 'update_route';
    }
}
