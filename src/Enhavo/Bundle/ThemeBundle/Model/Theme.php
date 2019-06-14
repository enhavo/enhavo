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
     * @var Webpack
     */
    private $webpack;

    /**
     * @var Template
     */
    private $template;

    /**
     * @var Block
     */
    private $block;

    /**
     * @return Meta
     */
    public function getMeta(): Meta
    {
        return $this->meta;
    }

    /**
     * @param Meta $meta
     */
    public function setMeta(Meta $meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @return Webpack
     */
    public function getWebpack(): Webpack
    {
        return $this->webpack;
    }

    /**
     * @param Webpack $webpack
     */
    public function setWebpack(Webpack $webpack): void
    {
        $this->webpack = $webpack;
    }

    /**
     * @return Template
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    /**
     * @param Template $template
     */
    public function setTemplate(Template $template): void
    {
        $this->template = $template;
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        return $this->block;
    }

    /**
     * @param Block $block
     */
    public function setBlock(Block $block): void
    {
        $this->block = $block;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }
}
