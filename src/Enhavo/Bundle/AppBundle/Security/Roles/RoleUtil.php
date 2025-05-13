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

class RoleUtil
{
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';
    public const ACTION_CREATE = 'create';
    public const ACTION_INDEX = 'index';

    public function getRoleName($resource, $action)
    {
        $className = get_class($resource);
        $classNameParts = explode('\\', $className);

        $company = array_shift($classNameParts);
        $resource = array_pop($classNameParts);

        $bundle = null;
        foreach ($classNameParts as $part) {
            $matches = [];
            if (preg_match('/([0-9a-zA-Z]+)Bundle/', $part, $matches)) {
                $bundle = $matches[1];
            }
        }

        $roleName = strtoupper(sprintf('ROLE_%s_%s_%s_%s', $company, $bundle, $resource, $action));

        return $roleName;
    }

    public function getAction($roleName)
    {
        if (preg_match('/^ROLE_.+_UPDATE$/', $roleName)) {
            return self::ACTION_UPDATE;
        }
        if (preg_match('/^ROLE_.+_DELETE$/', $roleName)) {
            return self::ACTION_DELETE;
        }
        if (preg_match('/^ROLE_.+_CREATE$/', $roleName)) {
            return self::ACTION_CREATE;
        }
        if (preg_match('/^ROLE_.+_INDEX$/', $roleName)) {
            return self::ACTION_INDEX;
        }

        return null;
    }

    public function getRoleNameByResourceName($bundlePrefix, $resourceName, $action)
    {
        $roleName = strtoupper(sprintf('ROLE_%s_%s_%s', $bundlePrefix, $resourceName, $action));

        return $roleName;
    }
}
