<?php
/**
 * UrlResolverInterface.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface UrlResolverInterface
{
    public function resolve($resource, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH);
}