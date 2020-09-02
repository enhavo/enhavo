<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-14
 * Time: 18:56
 */

namespace Enhavo\Bundle\ThemeBundle\Theme\Finder;

use Symfony\Component\Finder\Finder;

class DirThemeFinder
{
    /** @var string */
    private $dir;

    /**
     * DirThemeFinder constructor.
     * @param $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function find()
    {
        if(!file_exists($this->dir)) {
            return [];
        }

        $files = [];
        $finder = new Finder();
        $finder->files()->in($this->dir)->name('manifest.yml');
        foreach ($finder as $file) {
            if(!in_array($file->getRealPath(), $files)) {
                $files[] = $file->getRealPath();
            }
        }

        $finder->files()->in($this->dir)->name('manifest.yaml');
        foreach ($finder as $file) {
            if(!in_array($file->getRealPath(), $files)) {
                $files[] = $file->getRealPath();
            }
        }
        return $files;
    }
}
