<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 11.05.16
 * Time: 12:02
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;


class RoleUtil
{
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_CREATE = 'create';
    const ACTION_INDEX = 'index';

    public function getRoleName($resource, $action)
    {
        $className = get_class($resource);
        $classNameParts = explode('\\', $className);

        $company = array_shift($classNameParts);
        $resource = array_pop($classNameParts);

        $bundle = null;
        foreach($classNameParts as $part) {
            $matches = [];
            if(preg_match('/([0-9a-zA-Z]+)Bundle/', $part, $matches)) {
                $bundle = $matches[1];
            }
        }

        $roleName = strtoupper(sprintf('ROLE_%s_%s_%s_%s', $company, $bundle, $resource, $action));
        return $roleName;
    }

    public function getAction($roleName)
    {
        if(preg_match('/^ROLE_.+_UPDATE$/', $roleName)) {
            return self::ACTION_UPDATE;
        }
        if(preg_match('/^ROLE_.+_DELETE$/', $roleName)) {
            return self::ACTION_DELETE;
        }
        if(preg_match('/^ROLE_.+_CREATE$/', $roleName)) {
            return self::ACTION_CREATE;
        }
        if(preg_match('/^ROLE_.+_INDEX$/', $roleName)) {
            return self::ACTION_INDEX;
        }
        return null;
    }
}