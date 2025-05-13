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

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\ItemRepository;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaLibraryManager
{
    public function __construct(
        private readonly array $contentTypes,
        private readonly ItemRepository $itemRepository,
        private readonly TermRepository $termRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getTags()
    {
        return $this->termRepository->findByTaxonomy('media_library_tag');
    }

    public function getContentTypes()
    {
        $contentTypes = [];

        foreach ($this->contentTypes as $key => $config) {
            $contentTypes[$key] = $this->translator->trans($config['label'], [], 'EnhavoMediaLibraryBundle');
        }

        return $contentTypes;
    }

    public function getContentTypeIcon(?string $key)
    {
        if ($key && isset($this->contentTypes[$key]) && isset($this->contentTypes[$key]['icon'])) {
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
