<?php

namespace Enhavo\Bundle\SearchBundle\Tests\Mock;

/**
 * SplFileInfoMock.php
 *
 * @since 23/06/16
 * @author gseidel
 */
class SplFileInfoMock
{
    private $contents;

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }
}