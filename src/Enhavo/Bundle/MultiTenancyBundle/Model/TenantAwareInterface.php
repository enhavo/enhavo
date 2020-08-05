<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.08.18
 * Time: 15:49
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Model;


interface TenantAwareInterface
{
    public function getTenant();

    public function setTenant($key);
}
