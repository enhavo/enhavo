<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 24.05.16
 * Time: 15:30
 */

namespace Enhavo\Bundle\SearchBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Enhavo\Bundle\AppBundle\Security\Roles\RoleUtil;

class PermissionFilter implements SearchFilterInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function isGranted($resource)
    {
        $securityContext = $this->container->get('security.context');

        //check if user has the permission to see the resource
        $roleUtil = new RoleUtil();
        $role = $roleUtil->getRoleName($resource, 'index');
        if($securityContext->isGranted($role)){
            return true;
        }
        return false;
    }
}