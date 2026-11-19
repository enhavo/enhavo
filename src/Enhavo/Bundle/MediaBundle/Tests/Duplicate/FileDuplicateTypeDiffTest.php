<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Tests\Duplicate;

use Enhavo\Bundle\MediaBundle\Duplicate\Type\FileDuplicateType;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Change;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Tests\Duplicate\DiffTestTrait;
use PHPUnit\Framework\TestCase;

class FileDuplicateTypeDiffTest extends TestCase
{
    use DiffTestTrait;

    private function createFile(string $filename, string $checksum): File
    {
        $file = new File();
        $file->setFilename($filename);
        $file->setChecksum($checksum);

        return $file;
    }

    private function diff(?File $old, ?File $new): array
    {
        $duplicateFactory = $this->getMockBuilder(DuplicateFactory::class)->disableOriginalConstructor()->getMock();
        $factory = $this->createDiffFactory([new FileDuplicateType($duplicateFactory)], [
            DiffGallery::class => [
                'picture' => [['type' => 'file', 'groups' => ['revision']]],
            ],
        ]);

        $changes = $factory->diff(new DiffGallery($old), new DiffGallery($new), ['groups' => 'revision'])->getChanges();

        return array_map(fn (Change $change) => [$change->getField(), $change->getOld(), $change->getNew()], $changes);
    }

    public function testSameChecksumIsNoChange()
    {
        // a revision holds a copy of the file, so only the content counts
        $this->assertEquals([], $this->diff($this->createFile('a.jpg', 'abc'), $this->createFile('a.jpg', 'abc')));
    }

    public function testChangedFile()
    {
        $this->assertEquals([
            ['Picture', 'a.jpg', 'b.jpg'],
        ], $this->diff($this->createFile('a.jpg', 'abc'), $this->createFile('b.jpg', 'def')));

        $this->assertEquals([
            ['Picture', null, 'b.jpg'],
        ], $this->diff(null, $this->createFile('b.jpg', 'def')));
    }
}

class DiffGallery
{
    public function __construct(
        public ?File $picture = null,
    ) {
    }
}
