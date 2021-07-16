<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:49
 */

namespace Enhavo\Bundle\ThemeBundle\Theme\Loader;

use Enhavo\Bundle\ThemeBundle\Exception\ThemeLoadException;
use Enhavo\Bundle\ThemeBundle\Model\Meta;
use Enhavo\Bundle\ThemeBundle\Model\Template;
use Enhavo\Bundle\ThemeBundle\Model\Theme;
use Enhavo\Bundle\ThemeBundle\Model\Webpack;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Yaml\Yaml;

class FileThemeLoader implements ThemeLoaderInterface
{
    /** @var string */
    private $path;

    /** @var Serializer */
    private $serializer;

    /** @var Theme */
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

            if ($this->theme->getKey() === null) {
                throw ThemeLoadException::keyNotExists($this->path);
            }

            $this->theme->setPath(realpath(dirname($this->path)));
            $this->initTemplate();
            $this->initMeta();
        }
        return $this->theme;
    }

    private function initTemplate()
    {
        if ($this->theme->getTemplate() === null) {
            $this->theme->setTemplate(new Template());
        }

        if ($this->theme->getTemplate()->getPath() === null) {
            $this->theme->getTemplate()->setPath('templates');
        }

        $templatePath = $this->theme->getTemplate()->getPath();
        if (preg_match('/^[.a-zA-Z0-9]/', $templatePath)) {
            $this->theme->getTemplate()->setPath(sprintf('%s/%s', realpath(dirname($this->path)), $templatePath));
        }
    }

    private function initMeta()
    {
        if ($this->theme->getMeta() === null) {
            $this->theme->setMeta(new Meta());
        }
    }

    private function initWebpack()
    {
        if ($this->theme->getWebpack() === null) {
            $this->theme->setWebpack(new Webpack());
        }
    }
}
