<?php
/**
 * AsseticAdmin.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Twig;

class AdminAsset extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('admin_asset', array($this, 'render')),
        );
    }

    public function render($path)
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

    public function getName()
    {
        return 'admin_asset';
    }
}