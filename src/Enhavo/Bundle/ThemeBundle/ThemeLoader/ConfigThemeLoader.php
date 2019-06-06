<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:49
 */

namespace Enhavo\Bundle\ThemeBundle\ThemeLoader;

use Enhavo\Bundle\ThemeBundle\Model\Theme;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Config\FileLocatorInterface;

class ConfigThemeLoader implements ThemeLoaderInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var string
     */
    private $file;

    /**
     * @var FileLocatorInterface
     */
    private $locator;

    /**
     * @var Theme
     */
    private $theme;

    /**
     * ConfigThemeLoader constructor.
     * @param string $theme
     * @param string[] $themes
     * @param Serializer $serializer
     */
    public function __construct(string $theme, array $themes, Serializer $serializer, FileLocatorInterface $locator)
    {
        $this->file = $themes[$theme];
        $this->serializer = $serializer;
        $this->locator = $locator;
    }

    public function load()
    {
        if($this->theme === null) {
            $file = $this->locator->locate($this->file);
            $loader = new FileThemeLoader($file, $this->serializer);
            $this->theme = $loader->load();
        }
        return $this->theme;
    }
}
