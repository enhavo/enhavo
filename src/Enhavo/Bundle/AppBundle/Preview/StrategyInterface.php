<?php

namespace Enhavo\Bundle\AppBundle\Preview;

/**
 * @author gseidel
 */
interface StrategyInterface
{
    /**
     * @param $resource
     * @param array $options
     * @return mixed
     */
    public function getPreviewResponse($resource, $options = array());
}
