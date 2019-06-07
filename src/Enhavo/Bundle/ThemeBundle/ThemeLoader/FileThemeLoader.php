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
use Symfony\Component\Yaml\Yaml;

class FileThemeLoader implements ThemeLoaderInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Theme
     */
    private $theme;

    public function __construct(string $path, Serializer $serializer)
    {
        $this->path = $path;
        $this->serializer = $serializer;
    }

    public function load()
    {
        if($this->theme === null) {
            $config = Yaml::parse(file_get_contents($this->path));
            $this->theme = $this->serializer->denormalize($config, Theme::class, null, [
                'groups' => 'theme'
            ]);
        }
        return $this->theme;
    }
}
