<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 15:36
 */

namespace Enhavo\Bundle\ThemeBundle\Model;


class Block
{
    /**
     * @var string[]
     */
    private $columnStyles;

    /**
     * @var string[]
     */
    private $templates;

    /**
     * @return string[]
     */
    public function getColumnStyles(): array
    {
        return $this->columnStyles;
    }

    /**
     * @param string[] $columnStyles
     */
    public function setColumnStyles(array $columnStyles): void
    {
        $this->columnStyles = $columnStyles;
    }

    /**
     * @return string[]
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @param string[] $templates
     */
    public function setTemplates(array $templates): void
    {
        $this->templates = $templates;
    }
}
