<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Tests\Storage;

use Enhavo\Bundle\MediaBundle\Content\Content;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Storage\SymlinkEnhancedFileStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class SymlinkEnhancedFileStorageTest extends TestCase
{
    const STORAGE_DIR = __DIR__ . '/../fixtures/symlink-storage-test/storage';
    const PUBLIC_DIR = __DIR__ . '/../fixtures/symlink-storage-test/public';

    public function setUp(): void
    {
        $fs = new Filesystem();
        if ($fs->exists(self::STORAGE_DIR)) {
            $fs->remove(self::STORAGE_DIR);
        }

        if ($fs->exists(self::PUBLIC_DIR)) {
            $fs->remove(self::PUBLIC_DIR);
        }

        $fs->mkdir(self::STORAGE_DIR);
        $fs->mkdir(self::PUBLIC_DIR);
    }

    public function createDependencies()
    {
        $dependencies = new SymlinkEnhancedFileStorageDependencies();
        $dependencies->urlGenerator = $this->getMockBuilder(UrlGeneratorInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->publicDir = self::PUBLIC_DIR;
        $dependencies->storage = new StorageMock(self::STORAGE_DIR);
        $dependencies->fs = new Filesystem();

        return $dependencies;
    }

    public function createInstance(SymlinkEnhancedFileStorageDependencies $dependencies)
    {
        $instance = new SymlinkEnhancedFileStorage(
            $dependencies->urlGenerator,
            $dependencies->publicDir,
            $dependencies->storage,
            $dependencies->fs,
        );

        return $instance;
    }

    public function testSaveContent()
    {
        $dependencies = $this->createDependencies();
        $dependencies->urlGenerator->method('generate')->willReturn('/foobar');

        $instance = $this->createInstance($dependencies);

        $file = new File();
        $file->setBasename('hello.txt');
        $file->setContent(new Content('1'));

        $instance->saveContent($file);

        $this->assertEquals('1', file_get_contents(self::PUBLIC_DIR . '/foobar'));
    }

    public function testGetContent()
    {
        $dependencies = $this->createDependencies();
        $dependencies->urlGenerator->method('generate')->willReturn('/foobar');

        $instance = $this->createInstance($dependencies);

        $file = new File();
        $file->setBasename('hello.txt');
        $file->setContent(new Content('1'));

        $dependencies->storage->saveContent($file);

        $this->assertFalse(file_exists(self::PUBLIC_DIR . '/foobar'));

        $instance->getContent($file);

        $this->assertTrue(file_exists(self::PUBLIC_DIR . '/foobar'));
        $this->assertEquals('1', file_get_contents(self::PUBLIC_DIR . '/foobar'));
    }

    public function testDeleteContent()
    {
        $dependencies = $this->createDependencies();
        $dependencies->urlGenerator->method('generate')->willReturn('/foobar');

        $instance = $this->createInstance($dependencies);

        $file = new File();
        $file->setBasename('hello.txt');
        $file->setContent(new Content());

        $instance->saveContent($file);

        $this->assertTrue(file_exists(self::PUBLIC_DIR . '/foobar'));

        $instance->deleteContent($file);

        $this->assertFalse(file_exists(self::PUBLIC_DIR . '/foobar'));
    }
}

class SymlinkEnhancedFileStorageDependencies
{
    public UrlGeneratorInterface|MockObject $urlGenerator;
    public string $publicDir;
    public StorageInterface $storage;
    public Filesystem $fs;
}
