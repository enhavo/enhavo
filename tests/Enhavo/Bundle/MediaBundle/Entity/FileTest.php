<?php

namespace Enhavo\Bundle\MediaBundle\Entity;

/**
 * FileTest.php
 *
 * @since 29/12/16
 * @author gseidel
 */use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testSetParameters()
    {
        $file = new File();
        $file->setParameters([
            'foo' => 'bar',
            'hello' => 'world'
        ]);
        $file->setParameters([
            'hello' => 'world!'
        ]);
        $this->assertArraySubset([
            'foo' => 'bar',
            'hello' => 'world!'
        ] , $file->getParameters());
    }
}