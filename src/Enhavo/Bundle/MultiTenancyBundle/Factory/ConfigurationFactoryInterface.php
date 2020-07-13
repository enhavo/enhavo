<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 20.09.18
 * Time: 01:56
 */

namespace Bundle\MultiTenancyBundle\Factory;

use Bundle\MultiTenancyBundle\Model\MultiTenancyConfiguration;

interface ConfigurationFactoryInterface
{
    /**
     * @param $config
     * @return MultiTenancyConfiguration
     */
    public function create($config);
}
