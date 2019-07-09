<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 22:39
 */

namespace Enhavo\Bundle\AppBundle\Template;


class ConfigWebpackBuildResolver implements WebpackBuildResolverInterface
{
    /**
     * @var string
     */
    private $config;

    /**
     * ConfigWebpackBuildResolver constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function resolve()
    {
        return $this->config;
    }
}
