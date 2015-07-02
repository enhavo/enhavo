<?php
/**
 * AsseticAdmin.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Twig;

use enhavo\AdminBundle\Util\Assetic;

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
        $path = Assetic::convertPathToAsset($path);
        return $path;
    }

    public function getName()
    {
        return 'admin_asset';
    }
}