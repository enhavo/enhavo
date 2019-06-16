<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 15:35
 */

namespace Enhavo\Bundle\ThemeBundle\Model;


class Template
{
    /**
     * @var string[]
     */
    private $mapping;

    /**
     * @return string[]
     */
    public function getMapping(): array
    {
        return $this->mapping;
    }

    /**
     * @param string[] $mapping
     */
    public function setMapping(array $mapping): void
    {
        $this->mapping = $mapping;
    }
}
