<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-10
 * Time: 13:11
 */

namespace Enhavo\Component\Metadata;

class Metadata
{
    /** @var string */
    private $className;

    /**
     * Metadata constructor.
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }
}
