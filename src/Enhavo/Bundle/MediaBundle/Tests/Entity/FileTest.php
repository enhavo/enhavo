<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Entity;

/*
 * FileTest.php
 *
 * @since 29/12/16
 * @author gseidel
 */ use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testSetParameters()
    {
        $file = new File();
        $file->setParameters([
            'foo' => 'bar',
            'hello' => 'world',
        ]);
        $file->setParameters([
            'hello' => 'world!',
        ]);
        $this->assertSame([
            'foo' => 'bar',
            'hello' => 'world!',
        ], $file->getParameters());
    }
}
