<?php

namespace Enhavo\Bundle\MediaBundle\Entity;

/**
 * FileTest.php
 *
 * @since 29/12/16
 * @author gseidel
 */
class FileTest extends \PHPUnit_Framework_TestCase
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