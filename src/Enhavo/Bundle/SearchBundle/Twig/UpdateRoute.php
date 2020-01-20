<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.05.16
 * Time: 18:42
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
        return array(
            new TwigFilter('updateRoute', array($this, 'getUpdateRoute')),
        );
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
