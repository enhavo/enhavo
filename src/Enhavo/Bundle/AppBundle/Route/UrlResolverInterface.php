<?php
/**
 * UrlResolverInterface.php
 *
 * @since 06/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Route;


interface UrlResolverInterface
{
    public function resolve($resource);
}