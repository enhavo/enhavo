<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-13
 * Time: 16:33
 */

namespace Enhavo\Bundle\ThemeBundle\Theme;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ThemeBundle\Model\Theme;
use Enhavo\Bundle\ThemeBundle\Repository\ThemeRepository;
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

    /**
     * @var boolean
     */
    private $dynamicEnable;

    /**
     * @var string
     */
    private $theme = [];

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var bool
     */
    private $load = false;

    /**
     * ThemeManager constructor.
     * @param $themesConfig
     * @param Serializer $serializer
     * @param FileLocatorInterface $locator
     * @param $dynamicEnable
     * @param $theme
     * @param EntityRepository $repository
     */
    public function __construct($themesConfig, Serializer $serializer, FileLocatorInterface $locator, $dynamicEnable, $theme, EntityRepository $repository)
    {
        $this->themesConfig = $themesConfig;
        $this->serializer = $serializer;
        $this->locator = $locator;
        $this->dynamicEnable = $dynamicEnable;
        $this->theme = $theme;
        $this->repository = $repository;
    }

    public function getThemes()
    {
        $this->load();
        return $this->themes;
    }

    public function getTheme($key = null)
    {
        if($key === null) {
            $key = $this->getCurrentKey();
        }

        $this->load($key);
        return $this->themes[$key];
    }

    private function load(string $key = null)
    {
        if($key === null && $this->load) {
            return;
        }

        if($key !== null && isset($this->theme[$key])) {
            return;
        }

        foreach($this->themesConfig as $themeKey => $path) {
            if($key !== null && $key !== $themeKey) {
                continue;
            }
            if(!isset($this->theme[$themeKey])) {
                $file = $this->locator->locate($path);
                $loader = new FileThemeLoader($file, $this->serializer);
                $this->themes[$themeKey] = $loader->load();
            }
        }

        if($key === null) {
            $this->load = true;
        }
    }

    private function getCurrentKey()
    {
        if($this->dynamicEnable) {
            $theme = $this->repository->findOneBy([
                'active' => true
            ]);
            if($theme) {
                return $theme->getKey();
            }
        }
        return $this->theme;
    }
}
