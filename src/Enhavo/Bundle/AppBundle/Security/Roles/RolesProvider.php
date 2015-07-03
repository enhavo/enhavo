<?php
/**
 * RolesProvider.php
 *
 * @since 24/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\AppBundle\Security\Roles;

interface RolesProvider
{
    public function getRoles();
}