<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Preview;

/**
 * @author gseidel
 */
interface StrategyInterface
{
    /**
     * @param array $options
     */
    public function getPreviewResponse($resource, $options = []);
}
