<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 15:34
 */

namespace Enhavo\Bundle\ThemeBundle\Model;


class Webpack
{
    /**
     * @var string
     */
    private $build;

    /**
     * @var array
     */
    private $options;

    /**
     * @return string
     */
    public function getBuild(): string
    {
        return $this->build;
    }

    /**
     * @param string $build
     */
    public function setBuild(string $build): void
    {
        $this->build = $build;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
