<?php

namespace Enhavo\Bundle\AppBundle\Security\Roles;

/**
 * @author gseidel
 */
interface RolesProvider
{
    public function getRoles();
}
