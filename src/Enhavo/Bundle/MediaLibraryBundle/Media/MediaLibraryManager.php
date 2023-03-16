<?php
/**
 * @author blutze-media
 * @since 2022-02-24
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Media;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaLibraryManager
{
    private array $contentTypes;
    private FileRepository $fileRepository;
    private TermRepository $termRepository;

    private TranslatorInterface $translator;

    /**
     * @param array $contentTypes
     * @param FileRepository $fileRepository
     * @param TermRepository $termRepository
     * @param TranslatorInterface $translator
     */
    public function __construct(array $contentTypes, FileRepository $fileRepository, TermRepository $termRepository, TranslatorInterface $translator)
    {
        $this->contentTypes = $contentTypes;
        $this->fileRepository = $fileRepository;
        $this->termRepository = $termRepository;
        $this->translator = $translator;
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

    public function getContentTypeIcon(?string $key) {
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
                $regex = '%' . $matcher . '%';
                if (preg_match($regex, $mimeType)) {
                    return $key;
                }
            }
        }

        return null;
    }

}
