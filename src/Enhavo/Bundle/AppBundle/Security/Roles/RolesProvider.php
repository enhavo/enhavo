<?php
/**
 * RolesProvider.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AdminBundle\Security\Roles;

interface RolesProvider
{
    public function getRoles();
}