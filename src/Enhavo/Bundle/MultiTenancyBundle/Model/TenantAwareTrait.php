<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.09.18
 * Time: 10:04
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Model;


trait TenantAwareTrait
{
    /** @var string */
    private $tenant;

    /**
     * @return string
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * @param string $tenant
     */
    public function setTenant($tenant)
    {
        $this->tenant = $tenant;
    }
}
