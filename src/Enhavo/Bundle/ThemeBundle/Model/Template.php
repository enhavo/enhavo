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
     * @var string
     */
    private $path;

    /**
     * @return string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }
}
