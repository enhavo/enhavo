<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translator\Media;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\MediaBundle\Entity\File;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationFile;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationFileRepository;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translator\Media\FileTranslator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileTranslatorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new FileTranslatorTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(TranslationFileRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->entityManager->method('getRepository')->willReturn($dependencies->repository);
        $dependencies->entityResolver = $this->getMockBuilder(EntityResolverInterface::class)->getMock();
        $dependencies->localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)->getMock();
        $dependencies->localeProvider->method('getDefaultLocale')->willReturn('es');

        return $dependencies;
    }

    private function createInstance(FileTranslatorTestDependencies $dependencies)
    {
        $translator = new FileTranslator(
            $dependencies->entityManager,
            $dependencies->entityResolver,
            $dependencies->localeProvider
        );

        return $translator;
    }

    public function testSetTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var FileInterface|MockObject $file */
        $file = $this->getMockBuilder(FileInterface::class)->getMock();

        $entity = new TranslatableMock();

        $dependencies->repository->method('findOneBy')->willReturnCallback(function ($options) use ($file) {
            $translation = new TranslationFile();
            $translation->setFile($file);

            return $translation;
        });

        $translator->setTranslation($entity, 'file', 'fr', $file);
        $entity->id = 1;

        $this->assertEquals($file, $translator->getTranslation($entity, 'file', 'fr'));

    }

    public function testResetTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var FileInterface|MockObject $file */
        $file = new File();
        $file->setFilename('file-a');
        /** @var FileInterface|MockObject $file */
        $file2 = new File();
        $file2->setFilename('file-b');

        $dependencies->repository->method('findOneBy')->willReturnCallback(function () use ($file) {
            $translation = new TranslationFile();
            $translation->setFile($file);

            return $translation;
        });

        $entity = new TranslatableMock();

        $translator->setTranslation($entity, 'file', 'fr', $file2);

        $this->assertEquals($file2, $translator->getTranslation($entity, 'file', 'fr'));

    }

    public function testGetSetTranslationDefaultLocale()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();

        $this->assertNull($translator->getTranslation($entity, 'file', 'es'));
        $this->assertNull($translator->setTranslation($entity, 'file', 'es', []));
    }

    public function testGetExistingTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);
        /** @var FileInterface|MockObject $file */
        $file = $this->getMockBuilder(FileInterface::class)->getMock();

        $dependencies->repository->method('findOneBy')->willReturnCallback(function () use ($file) {
            $translation = new TranslationFile();
            $translation->setFile($file);

            return $translation;
        });

        $entity = new TranslatableMock();

        $this->assertEquals($file,
            $translator->getTranslation($entity, 'file', 'fr')
        );
    }

    public function testGetTranslationMissing()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();

        $this->assertNull($translator->getTranslation($entity, 'file', 'fr'));
    }

    public function testDelete()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $translation = new TranslationFile();

        $dependencies->repository->expects($this->once())->method('findBy')->willReturnCallback(function () use ($translation) {
            return [$translation];
        });
        $dependencies->entityManager->expects($this->once())->method('remove');

        $entity = new TranslatableMock();

        $translator->delete($entity, 'file');


    }

    public function testTranslate()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var FileInterface|MockObject $file */
        $file = new File();
        $file->setFilename('file-a');
        /** @var FileInterface|MockObject $file2 */
        $file2 = new File();
        $file2->setFilename('file-b');

        $entity = new TranslatableMock();
        $entity->setFile($file);

        $translator->setTranslation($entity, 'file', 'en', $file2);
        $translator->translate($entity, 'file', 'en', []);
        $this->assertNotEquals($file, $entity->getFile());
    }


    public function testDetach()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        /** @var FileInterface|MockObject $file */
        $file = new File();
        $file->setFilename('file');
        /** @var FileInterface|MockObject $file */
        $fileEn = new File();
        $fileEn->setFilename('file-en');

        $entity = new TranslatableMock();
        $entity->setName('filed');
        $entity->setFile($file);

        $translator->setTranslation($entity, 'file', 'en', $fileEn);

        $translator->translate($entity, 'file', 'en', []);
        $translator->detach($entity, 'file', 'en', []);

        $this->assertEquals($file, $entity->getFile());

        $translator->translate($entity, 'file', 'es', []);
        $this->assertEquals($file, $entity->getFile());
    }

}

class FileTranslatorTestDependencies
{
    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var EntityResolverInterface|MockObject */
    public $entityResolver;

    /** @var TranslationFileRepository|MockObject */
    public $repository;

    /** @var LocaleProviderInterface|MockObject */
    public $localeProvider;
}
