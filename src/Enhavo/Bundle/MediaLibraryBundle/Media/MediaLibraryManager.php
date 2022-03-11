<?php
/**
 * @author blutze-media
 * @since 2022-02-24
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Media;

use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;

class MediaLibraryManager
{
    /** @var FileRepository */
    private $fileRepository;

    /** @var TermRepository */
    private $termRepository;

    /**
     * @param FileRepository $fileRepository
     * @param TermRepository $termRepository
     */
    public function __construct(FileRepository $fileRepository, TermRepository $termRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->termRepository = $termRepository;
    }


    public function getTags()
    {
        return $this->termRepository->findByTaxonomy('media_library_tag');
    }

    public function getContentTypes()
    {
        return ['audio', 'archive', 'document', 'executable', 'image', 'video'];
    }

    public function getFiles($contentType, $tag)
    {
        return $this->fileRepository->findByContentTypeAndTags($contentType, $tag?[$tag]:[]);
    }
}
