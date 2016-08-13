<?php
/**
 * StrategyInterface.php
 *
 * @since 18/11/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Preview;

interface StrategyInterface
{
    /**
     * @param $resource
     * @param array $options
     * @return mixed
     */
    public function getPreviewResponse($resource, $options = array());
}