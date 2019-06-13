<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-13
 * Time: 16:33
 */

namespace Enhavo\Bundle\ThemeBundle\Theme;
use Enhavo\Bundle\ThemeBundle\Model\Theme;
use Enhavo\Bundle\ThemeBundle\Theme\Loader\FileThemeLoader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Serializer\Serializer;

class ThemeManager
{
    /**
     * @var array
     */
    private $themesConfig;

    /**
     * @var Theme[]
     */
    private $themes;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    public function __construct($themesConfig, Serializer $serializer, FileLocatorInterface $locator)
    {
        $this->themesConfig = $themesConfig;
        $this->serializer = $serializer;
        $this->locator = $locator;
    }

    public function getThemes()
    {
        if($this->themes === null) {
            $this->themes = [];
            foreach($this->themesConfig as $key => $path) {
                $file = $this->locator->locate($path);
                $loader = new FileThemeLoader($file, $this->serializer);
                $this->themes[$key] = $loader->load();
            }
        }
        return $this->themes;
    }
}
