<?php
/**
 * SimpleRequestConfigurationFactoryInterface.php
 *
 * @since 28/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface SimpleRequestConfigurationFactoryInterface
{
    /**
     * @param Request $request
     *
     * @return SimpleRequestConfiguration
     */
    public function createSimple(Request $request);
}