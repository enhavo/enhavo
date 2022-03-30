<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AbstractViewController;
use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Enhavo\Bundle\MediaLibraryBundle\Viewer\MediaLibraryViewer;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaLibraryController extends AbstractViewController
{
    /** @var MediaLibraryManager */
    private $mediaLibraryManager;

    /** @var FileRepository */
    private $fileRepository;

    public function __construct(ViewFactory $viewFactory, ViewHandlerInterface $viewHandler, MediaLibraryManager $mediaLibraryManager, FileRepository $fileRepository)
    {
        parent::__construct($viewFactory, $viewHandler);
        $this->mediaLibraryManager = $mediaLibraryManager;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $view = $this->viewFactory->create('media_library');
        return $this->viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function selectAction(Request $request): Response
    {
        $view = $this->viewFactory->create('media_library', [
            'multiple' => $request->get('multiple', false),
            'mode' => MediaLibraryViewer::MODE_SELECT,
        ]);
        return $this->viewHandler->handle($view);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request): JsonResponse
    {
        $items = $this->createFileList($request->get('content_type'), $request->get('tag'));

        return new JsonResponse([
            'items' => $items
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function tagsAction(Request $request): JsonResponse
    {
        $tags = $this->createTagList();

        return new JsonResponse([
            'tags' => $tags,
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function contentTypesAction(Request $request): JsonResponse
    {
        $types = $this->createContentTypeList();

        return new JsonResponse([
            'content_types' => $types,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $file = $this->fileRepository->find($id);

        return new JsonResponse([
            'id' => $file->getId(),
            'filename' => $file->getFilename(),
        ]);
    }

    public function notify(Request $request)
    {
        return new JsonResponse();
    }


    private function createTagList(): array
    {
        $terms = $this->mediaLibraryManager->getTags();
        $tags = [];
        foreach ($terms as $term) {
            $tags[] = [
                'id' => $term->getId(),
                'slug' => $term->getSlug(),
                'label' => $term->getName(),
            ];
        }
        return $tags;
    }

    private function createContentTypeList(): array
    {
        $terms = $this->mediaLibraryManager->getContentTypes();
        $contentTypes = [];
        foreach ($terms as $term) {
            $contentTypes[] = [
                'key' => $term,
                'label' => ucfirst($term),
            ];
        }
        return $contentTypes;
    }

    private function createFileList($contentType, $tag): array
    {
        $files = $this->mediaLibraryManager->getFiles($contentType, $tag);
        $items = [];
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->get('enhavo_media.media.public_url_generator');
        foreach ($files as $file) {
            $items[] = [
                'id' => $file->getId(),
                'previewImageUrl' => $urlGenerator->generateFormat($file, 'enhavoMediaLibraryThumb'),
                'label' => $file->getFilename(),
            ];
        }

        return $items;
    }
}
