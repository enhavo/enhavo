<?php
/**
 * StrategyInterface.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Preview;

use Enhavo\Bundle\AppBundle\Config\ConfigParser;

interface StrategyInterface
{
    /**
     * @param $resource
     * @param ConfigParser $config
     * @return mixed
     */
    public function getPreviewResponse($resource, ConfigParser $config);
}