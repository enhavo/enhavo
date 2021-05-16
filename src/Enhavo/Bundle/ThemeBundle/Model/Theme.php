<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-06
 * Time: 18:50
 */

namespace Enhavo\Bundle\ThemeBundle\Model;


class Theme
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var Meta
     */
    private $meta;

    /**
     * @var Template
     */
    private $template;

    /**
     * @var string
     */
    private $path;

    /**
     * @return Meta
     */
    public function getMeta(): ?Meta
    {
        return $this->meta;
    }

    /**
     * @param Meta $meta
     */
    public function setMeta(?Meta $meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @return Template
     */
    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @param Template $template
     */
    public function setTemplate(?Template $template): void
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
