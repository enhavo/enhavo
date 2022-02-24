<?php
/**
 * @author blutze-media
 * @since 2022-02-24
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Media;

use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Enhavo\Bundle\TaxonomyBundle\Repository\TermRepository;

class MediaLibraryManager
{
    /** @var FileRepository */
    private $fileRepository;

    /** @var TermRepository */
    private $termRepository;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /**
     * @param FileRepository $fileRepository
     * @param TermRepository $termRepository
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(FileRepository $fileRepository, TermRepository $termRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->fileRepository = $fileRepository;
        $this->termRepository = $termRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function createTagList(): array
    {
        $terms = $this->termRepository->findByTaxonomy('media_library_tag');
        $tags = [];
        foreach ($terms as $term) {
            $tags[] = [
                'title' => $term->getName(),
            ];
        }
        return $tags;
    }

    public function createItemList($tab, $tag): array
    {
        $files = $this->fileRepository->findByContentTypeAndTags($tab, $tag?[$tag]:[]);
        $items = [];
        foreach ($files as $file) {
            $items[] = [
                'id' => $file->getId(),
                'previewImageUrl' => $this->urlGenerator->generateFormat($file, 'enhavoMediaLibraryThumb'),
                'name' => $file->getFilename(),
            ];
        }

        return $items;
    }
}
