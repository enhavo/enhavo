<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Media;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Model\ItemInterface;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaLibraryManager
{
    public function __construct(
        private readonly array $contentTypes,
        private readonly FactoryInterface $itemFactory,
        private readonly TermRepository $termRepository,
        private readonly TranslatorInterface $translator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function createItem(FileInterface $file): ItemInterface
    {
        /** @var ItemInterface $item */
        $item = $this->itemFactory->createNew();
        $item->setFile($file);
        $file->setGarbage(false);

        return $item;
    }

    public function replaceFile(ItemInterface $item, FileInterface $file): void
    {
        $oldFile = $item->getFile();
        $usedFiles = $item->getUsedFiles();

        $oldFile->setChecksum($file->getChecksum());
        $oldFile->setToken($file->getToken());
        $oldFile->setContent($file->getContent());

        if ($oldFile instanceof File) {
            $formats = $oldFile->getFormats();
            foreach ($formats as $format) {
                $this->entityManager->remove($format);
            }
        }
        foreach ($usedFiles as $usedFile) {
            $usedFile->setChecksum($file->getChecksum());
            $usedFile->setToken($file->getToken());
            $usedFile->setContent($file->getContent());
            if ($usedFile instanceof File) {
                $formats = $usedFile->getFormats();
                foreach ($formats as $format) {
                    $this->entityManager->remove($format);
                }
            }
        }
    }

    public function getTags()
    {
        return $this->termRepository->findByTaxonomy('media_library_tag');
    }

    public function getContentTypes(): array
    {
        $contentTypes = [];

        foreach ($this->contentTypes as $key => $config) {
            $contentTypes[$key] = $this->translator->trans($config['label'], [], 'EnhavoMediaLibraryBundle');
        }

        return $contentTypes;
    }

    public function getContentTypeIcon(?string $key)
    {
        if ($key && isset($this->contentTypes[$key]['icon'])) {
            return $this->contentTypes[$key]['icon'];
        }

        return '';
    }

    public function matchContentType(FileInterface $file): ?string
    {
        $mimeType = $file->getMimeType();
        foreach ($this->contentTypes as $key => $config) {
            $matchers = $config['mime_types'];
            foreach ($matchers as $matcher) {
                $regex = '%'.$matcher.'%';
                if (preg_match($regex, $mimeType)) {
                    return $key;
                }
            }
        }

        return null;
    }
}
