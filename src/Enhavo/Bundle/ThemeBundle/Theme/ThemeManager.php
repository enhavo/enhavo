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
use Enhavo\Bundle\ThemeBundle\Theme\Finder\DirThemeFinder;
use Enhavo\Bundle\ThemeBundle\Theme\Loader\FileThemeLoader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Serializer\Serializer;

class ThemeManager
{
    /**
     * @var string[]
     */
    private $themesDir;

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
    private $theme;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var string
     */
    private $customFile;

    /**
     * ThemeManager constructor.
     * @param $themesDir
     * @param Serializer $serializer
     * @param FileLocatorInterface $locator
     * @param $dynamicEnable
     * @param $theme
     * @param EntityRepository $repository
     * @param string $customFile
     */
    public function __construct(
        $themesDir,
        Serializer $serializer,
        FileLocatorInterface $locator,
        $dynamicEnable,
        $theme,
        EntityRepository $repository,
        $customFile
    ) {
        $this->themesDir = $themesDir;
        $this->serializer = $serializer;
        $this->locator = $locator;
        $this->dynamicEnable = $dynamicEnable;
        $this->theme = $theme;
        $this->repository = $repository;
        $this->customFile = $customFile;
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

        $this->load();
        return $this->themes[$key];
    }

    private function load()
    {
        if($this->themes !== null) {
            $this->themes = [];
        }

        foreach($this->themesDir as $dir)
        {
            $finder = new DirThemeFinder($dir);
            $files = $finder->find();
            foreach($files as $file) {
                $loader = new FileThemeLoader($file, $this->serializer);
                $theme = $loader->load();
                $this->themes[$theme->getKey()] = $theme;
            }
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

    public function buildWebpackOption()
    {

    }
}
