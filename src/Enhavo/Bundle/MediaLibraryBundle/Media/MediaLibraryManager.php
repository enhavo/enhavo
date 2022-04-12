<?php
/**
 * @author blutze-media
 * @since 2022-02-24
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Media;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;

class MediaLibraryManager
{
    private array $contentTypes;
    private FileRepository $fileRepository;
    private TermRepository $termRepository;

    /**
     * @param array $contentTypes
     * @param FileRepository $fileRepository
     * @param TermRepository $termRepository
     */
    public function __construct(array $contentTypes, FileRepository $fileRepository, TermRepository $termRepository)
    {
        $this->contentTypes = $contentTypes;
        $this->fileRepository = $fileRepository;
        $this->termRepository = $termRepository;
    }


    public function getTags()
    {
        return $this->termRepository->findByTaxonomy('media_library_tag');
    }

    public function getContentTypes()
    {
        $contentTypes = [];

        foreach ($this->contentTypes as $key => $config) {
            $contentTypes[$key] = $config['label'];
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
