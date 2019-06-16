<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:48
 */

namespace Enhavo\Bundle\ThemeBundle\Theme\Loader;

use Enhavo\Bundle\ThemeBundle\Model\Theme;

interface ThemeLoaderInterface
{
    /**
     * @return Theme
     */
    public function load();
}
