<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-08
 * Time: 22:39
 */

namespace Enhavo\Bundle\ThemeBundle\Theme;

use Enhavo\Bundle\AppBundle\Template\WebpackBuildResolverInterface;

class ThemeWebpackBuildResolver implements WebpackBuildResolverInterface
{
    /**
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * ConfigWebpackBuildResolver constructor.
     * @param ThemeManager $themeManager
     */
    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    public function resolve()
    {
        return $this->themeManager->getTheme()->getKey();
    }
}
