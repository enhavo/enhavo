<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.09.18
 * Time: 10:04
 */

namespace Bundle\MultiTenancyBundle\Model;


trait MultiTenancyAwareTrait
{
    /**
     * @var string
     */
    private $multiTenancy;

    /**
     * @return string
     */
    public function getMultiTenancy()
    {
        return $this->multiTenancy;
    }

    /**
     * @param string $multiTenancy
     */
    public function setMultiTenancy($multiTenancy)
    {
        $this->multiTenancy = $multiTenancy;
    }
}
