<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Duplicate;

use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Change;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\CollectionDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\ModelDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\PropertyDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\ReferenceDuplicateType;
use PHPUnit\Framework\TestCase;

class DiffFactoryTest extends TestCase
{
    use DiffTestTrait;

    private function createTypes(): array
    {
        $duplicateFactory = $this->getMockBuilder(DuplicateFactory::class)->disableOriginalConstructor()->getMock();

        return [
            new PropertyDuplicateType(),
            new ReferenceDuplicateType(),
            new ModelDuplicateType($duplicateFactory),
            new CollectionDuplicateType($duplicateFactory),
        ];
    }

    /**
     * @param Change[] $changes
     */
    private function normalize(array $changes): array
    {
        return array_map(fn (Change $change) => [$change->getField(), $change->getOld(), $change->getNew()], $changes);
    }

    public function testChangedProperties()
    {
        $factory = $this->createDiffFactory($this->createTypes(), [
            DiffBook::class => [
                'title' => [['type' => 'property', 'groups' => ['revision']]],
                'pageCount' => [['type' => 'property', 'groups' => ['revision']]],
                'published' => [['type' => 'property', 'groups' => ['revision']]],
            ],
        ]);

        $diff = $factory->diff(
            new DiffBook('Old <b>title</b>', 100, false),
            new DiffBook('New title', 100, true),
            ['groups' => 'revision'],
        );

        $this->assertEquals([
            ['Title', 'Old title', 'New title'],
            ['Published', 'diff.value.no', 'diff.value.yes'],
        ], $this->normalize($diff->getChanges()));
    }

    public function testOnlyApplicableGroupAndOptions()
    {
        $factory = $this->createDiffFactory($this->createTypes(), [
            DiffBook::class => [
                'title' => [
                    ['type' => 'property', 'groups' => ['duplicate']],
                    ['type' => 'property', 'groups' => ['revision'], 'label' => 'book.label.title'],
                    ['type' => 'property', 'groups' => ['revision', 'restore']],
                ],
                'pageCount' => [['type' => 'property', 'groups' => ['duplicate']]],
                'published' => [['type' => 'property', 'groups' => ['revision'], 'diff' => false]],
            ],
        ]);

        $diff = $factory->diff(new DiffBook('A', 1, false), new DiffBook('B', 2, true), ['groups' => 'revision']);

        // first applicable config only, with its label; other groups and diff: false are skipped
        $this->assertEquals([
            ['book.label.title', 'A', 'B'],
        ], $this->normalize($diff->getChanges()));
    }

    public function testModelAndCollection()
    {
        $factory = $this->createDiffFactory($this->createTypes(), [
            DiffBook::class => [
                'author' => [['type' => 'model', 'groups' => ['revision']]],
                'chapters' => [['type' => 'collection', 'groups' => ['revision']]],
            ],
            DiffAuthor::class => [
                'name' => [['type' => 'property', 'groups' => ['revision']]],
            ],
            DiffChapter::class => [
                'title' => [['type' => 'property', 'groups' => ['revision']]],
            ],
        ]);

        $old = new DiffBook();
        $old->author = new DiffAuthor('Anna');
        $old->chapters = [new DiffChapter('One'), new DiffChapter('Two')];

        $new = new DiffBook();
        $new->author = new DiffAuthor('Bert');
        $new->chapters = [new DiffChapter('One'), new DiffChapter('Zwei'), new DiffChapter('Three')];

        $diff = $factory->diff($old, $new, ['groups' => 'revision']);

        $this->assertEquals([
            ['Author › Name', 'Anna', 'Bert'],
            ['Chapters › 2 › Title', 'Two', 'Zwei'],
            ['Chapters › 3 › Title', null, 'Three'],
        ], $this->normalize($diff->getChanges()));
    }

    public function testReferenceByLabel()
    {
        $factory = $this->createDiffFactory($this->createTypes(), [
            DiffBook::class => [
                'author' => [['type' => 'reference', 'groups' => ['revision']]],
            ],
        ]);

        $old = new DiffBook();
        $old->author = new DiffAuthor('Anna');
        $new = new DiffBook();
        $new->author = new DiffAuthor('Anna');

        // another object with the same label is no change
        $this->assertEquals([], $factory->diff($old, $new, ['groups' => 'revision'])->getChanges());

        $new->author = new DiffAuthor('Bert');
        $this->assertEquals([
            ['Author', 'Anna', 'Bert'],
        ], $this->normalize($factory->diff($old, $new, ['groups' => 'revision'])->getChanges()));
    }

    public function testDifferentClassesAreRemovedAndAdded()
    {
        $factory = $this->createDiffFactory($this->createTypes(), [
            DiffBook::class => [
                'author' => [['type' => 'model', 'groups' => ['revision']]],
            ],
            DiffAuthor::class => [
                'name' => [['type' => 'property', 'groups' => ['revision']]],
            ],
            DiffChapter::class => [
                'title' => [['type' => 'property', 'groups' => ['revision']]],
            ],
        ]);

        $old = new DiffBook();
        $old->author = new DiffAuthor('Anna');
        $new = new DiffBook();
        $new->author = new DiffChapter('Intro');

        $this->assertEquals([
            ['Author › Name', 'Anna', null],
            ['Author › Title', null, 'Intro'],
        ], $this->normalize($factory->diff($old, $new, ['groups' => 'revision'])->getChanges()));
    }
}

class DiffBook
{
    public ?object $author = null;
    public array $chapters = [];

    public function __construct(
        public ?string $title = null,
        public ?int $pageCount = null,
        public ?bool $published = null,
    ) {
    }
}

class DiffAuthor
{
    public function __construct(
        public ?string $name = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}

class DiffChapter
{
    public function __construct(
        public ?string $title = null,
    ) {
    }
}
