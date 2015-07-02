<?php
/**
 * Assetic.php
 *
 * @since 11/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Util;

class Assetic
{
    public static function convertPathToAsset($path)
    {
        $path = str_replace('@', '', $path);
        $pathArray = explode('/', $path);
        $bundle = str_replace('Bundle', '', array_shift($pathArray));
        $bundle = str_replace('@', '', $bundle);
        $bundle = strtolower($bundle);
        array_shift($pathArray);
        array_shift($pathArray);
        $path = implode('/', $pathArray);
        $path = sprintf('/bundles/%s/%s', $bundle, $path);
        return $path;
    }
}