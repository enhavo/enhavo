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
    public function create(Request $request);
}