<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Duplicate;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\FrameworkBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\Duplicate;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\Metadata\Metadata as DuplicateMetadata;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\BaseDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\PropertyDuplicateType;
use Enhavo\Bundle\TranslationBundle\Client\TranslationClientInterface;
use Enhavo\Bundle\TranslationBundle\Duplicate\Extension\TranslationPropertyDuplicateTypeExtension;
use Enhavo\Bundle\TranslationBundle\Entity\Translation as TranslationEntity;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata as TranslationMetadata;
use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TextTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\Factory;
use Enhavo\Component\Type\Registry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Runs the duplicate calls of RevisionManager::saveRevision() and RevisionManager::restore() with the real duplicate
 * and translation services. Only the database is replaced by an in-memory store, and every step uses fresh translator
 * buffers to simulate a new request.
 */
class TranslationRestoreTest extends TestCase
{
    /** @var TranslationEntity[] */
    private array $database = [];

    private DuplicateFactory $duplicateFactory;

    protected function setUp(): void
    {
        $this->database = [];
        $this->duplicateFactory = $this->createDuplicateFactory();
    }

    private function createTranslationManager(): TranslationManager
    {
        $localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)->getMock();
        $localeProvider->method('getLocales')->willReturn(['de', 'en', 'fr']);
        $localeProvider->method('getDefaultLocale')->willReturn('de');

        $entityResolver = $this->getMockBuilder(EntityResolverInterface::class)->getMock();
        $entityResolver->method('getName')->willReturnCallback(fn ($entity) => get_class($entity));

        $repository = $this->getMockBuilder(TranslationRepository::class)->disableOriginalConstructor()->getMock();
        $repository->method('findBy')->willReturnCallback(function (array $criteria) {
            return array_values(array_filter($this->database, function (TranslationEntity $translation) use ($criteria) {
                return $translation->getClass() === $criteria['class']
                    && $translation->getRefId() === $criteria['refId']
                    && (!isset($criteria['property']) || $translation->getProperty() === $criteria['property']);
            }));
        });

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $entityManager->method('getRepository')->willReturn($repository);
        $entityManager->method('persist')->willReturnCallback(function ($entity) {
            if ($entity instanceof TranslationEntity && !in_array($entity, $this->database, true)) {
                $this->database[] = $entity;
            }
        });

        $textTranslator = new TextTranslator($entityManager, $entityResolver, $localeProvider);

        $container = new Container();
        $container->set(TranslationType::class, new TranslationType());
        $container->set(TextTranslationType::class, new TextTranslationType(
            $textTranslator,
            $this->getMockBuilder(TranslationClientInterface::class)->getMock(),
            'de',
        ));
        $registry = new Registry('Translation');
        $registry->setContainer($container);
        $registry->register(TranslationType::class, TranslationType::class);
        $registry->register(TextTranslationType::class, TextTranslationType::class);

        $titleNode = new PropertyNode();
        $titleNode->setProperty('title');
        $titleNode->setType('text');
        $metadata = new TranslationMetadata(RestoreEntity::class);
        $metadata->addProperty($titleNode);

        $metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $metadataRepository->method('hasMetadata')->willReturn(true);
        $metadataRepository->method('getMetadata')->willReturn($metadata);

        return new TranslationManager(
            $metadataRepository,
            new Factory(Translation::class, $registry),
            $entityManager,
            $this->getMockBuilder(LocaleResolverInterface::class)->getMock(),
            $entityResolver,
            $localeProvider,
            true,
        );
    }

    /**
     * The duplicate type extensions keep a reference to the translation manager, so a new request means new duplicate services.
     */
    private function newRequest(): TranslationManager
    {
        $translationManager = $this->createTranslationManager();
        $this->duplicateFactory = $this->createDuplicateFactory($translationManager);

        return $translationManager;
    }

    private function createDuplicateFactory(?TranslationManager $translationManager = null): DuplicateFactory
    {
        $container = new Container();
        $container->set(BaseDuplicateType::class, new BaseDuplicateType());
        $container->set(PropertyDuplicateType::class, new PropertyDuplicateType());
        $container->set(TranslationPropertyDuplicateTypeExtension::class, new TranslationPropertyDuplicateTypeExtension($translationManager ?? $this->createTranslationManager()));
        $registry = new Registry('Duplicate');
        $registry->setContainer($container);
        $registry->register(BaseDuplicateType::class, BaseDuplicateType::class);
        $registry->register(PropertyDuplicateType::class, PropertyDuplicateType::class);
        $registry->registerExtension(TranslationPropertyDuplicateTypeExtension::class, TranslationPropertyDuplicateTypeExtension::class);

        $metadata = new DuplicateMetadata(RestoreEntity::class);
        $metadata->setProperties([
            'title' => [
                ['type' => 'property', 'groups' => ['revision', 'restore']],
            ],
        ]);
        $metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $metadataRepository->method('getMetadata')->willReturn($metadata);

        return new DuplicateFactory($metadataRepository, new Factory(Duplicate::class, $registry));
    }

    /**
     * Simulates the flush: the doctrine extension reference fills class and refId of new translation rows.
     */
    private function flush(): void
    {
        foreach ($this->database as $translation) {
            if (null === $translation->getRefId()) {
                $translation->setClass(get_class($translation->getObject()));
                $translation->setRefId($translation->getObject()->getId());
            }
        }
    }

    private function createSubject(array $translations): RestoreEntity
    {
        $translationManager = $this->newRequest();
        $subject = new RestoreEntity(1, $translations['de']);
        foreach ($translations as $locale => $value) {
            $translationManager->setTranslation($subject, 'title', $locale, $value);
        }
        $this->flush();

        return $subject;
    }

    private function saveRevision(RestoreEntity $subject): RestoreEntity
    {
        $this->newRequest();
        /** @var RestoreEntity $revision */
        $revision = $this->duplicateFactory->duplicate($subject, null, ['groups' => 'revision']);
        $revision->id = 2;
        $this->flush();

        return $revision;
    }

    private function updateSubject(RestoreEntity $subject, array $translations): void
    {
        $translationManager = $this->newRequest();
        $subject->title = $translations['de'];
        foreach ($translations as $locale => $value) {
            $translationManager->setTranslation($subject, 'title', $locale, $value);
        }
        $this->flush();
    }

    private function restore(RestoreEntity $revision, RestoreEntity $subject): void
    {
        $this->newRequest();
        $this->duplicateFactory->duplicate($revision, $subject, ['groups' => 'restore']);
        $this->flush();
    }

    private function getTitles(RestoreEntity $entity): array
    {
        $translationManager = $this->newRequest();

        return [
            'de' => $translationManager->getProperty($entity, 'title', 'de'),
            'en' => $translationManager->getProperty($entity, 'title', 'en'),
            'fr' => $translationManager->getProperty($entity, 'title', 'fr'),
        ];
    }

    private function countRows(RestoreEntity $entity): int
    {
        return count(array_filter($this->database, fn (TranslationEntity $translation) => $translation->getRefId() === $entity->getId()));
    }

    public function testRestoreTranslations()
    {
        $subject = $this->createSubject(['de' => 'Titel v1', 'en' => 'Title v1', 'fr' => 'Titre v1']);

        $revision = $this->saveRevision($subject);
        $this->assertEquals(['de' => 'Titel v1', 'en' => 'Title v1', 'fr' => 'Titre v1'], $this->getTitles($revision));

        $this->updateSubject($subject, ['de' => 'Titel v2', 'en' => 'Title v2', 'fr' => 'Titre v2']);
        $this->assertEquals(['de' => 'Titel v2', 'en' => 'Title v2', 'fr' => 'Titre v2'], $this->getTitles($subject));

        $this->restore($revision, $subject);

        $this->assertEquals(['de' => 'Titel v1', 'en' => 'Title v1', 'fr' => 'Titre v1'], $this->getTitles($subject));
        // existing rows are updated, no additional rows are created
        $this->assertEquals(2, $this->countRows($subject));
        // the revision itself is untouched
        $this->assertEquals(['de' => 'Titel v1', 'en' => 'Title v1', 'fr' => 'Titre v1'], $this->getTitles($revision));
    }

    public function testRestoreKeepsTranslationsMissingInRevision()
    {
        $subject = $this->createSubject(['de' => 'Titel v1', 'en' => 'Title v1']);
        $revision = $this->saveRevision($subject);

        $this->updateSubject($subject, ['de' => 'Titel v2', 'en' => 'Title v2', 'fr' => 'Titre v2']);

        $this->restore($revision, $subject);

        $this->assertEquals(['de' => 'Titel v1', 'en' => 'Title v1', 'fr' => 'Titre v2'], $this->getTitles($subject));
    }
}

class RestoreEntity
{
    public function __construct(
        public ?int $id = null,
        public ?string $title = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
