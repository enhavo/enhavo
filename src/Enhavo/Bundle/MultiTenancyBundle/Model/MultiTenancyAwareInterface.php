<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.08.18
 * Time: 15:49
 */

namespace Bundle\MultiTenancyBundle\Model;


interface MultiTenancyAwareInterface
{
    public function getMultiTenancy();

    public function setMultiTenancy($key);
}
