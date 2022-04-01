<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\MediaBundle\Controller\FileControllerTrait;
use Enhavo\Bundle\MediaBundle\Exception\StorageException;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Factory\FileFactory;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Enhavo\Bundle\MediaLibraryBundle\Viewer\MediaLibraryViewer;
use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MediaLibraryController extends ResourceController
{
    use FileControllerTrait;
    private FactoryInterface $viewFactory;

    /**
     * @return MediaLibraryManager
     */
    private function getMediaLibraryManager(): MediaLibraryManager
    {
        return $this->container->get('Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager');
    }

    /**
     * @return MediaManager
     */
    private function getMediaManager(): MediaManager
    {
        return $this->container->get('enhavo_media.media.media_manager');
    }

    /**
     * @return FileRepository
     */
    private function getFileRepository(): FileRepository
    {
        return $this->container->get('enhavo_media.repository.file');
    }

    /**
     * @return FileFactory
     */
    private function getFileFactory(): FileFactory
    {
        return $this->container->get('enhavo_media_library.factory.media_library');
    }

    /**
     * @return FactoryInterface
     */
    private function getViewFactory(): FactoryInterface
    {
        return $this->viewFactory;
    }



    /**
     * @param FactoryInterface $viewFactory
     */
    public function setViewFactory(FactoryInterface $viewFactory): void
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var  $view */
        $view = $this->getViewFactory()->create([
            'type' => 'media_library',
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);
        return $view->getResponse($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function selectAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $view = $this->getViewFactory()->create([
            'type' => 'media_library',
            'multiple' => $request->get('multiple', false),
            'mode' => MediaLibraryViewer::MODE_SELECT,
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);
        return $view->getResponse($request);
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
        $file = $this->getFileRepository()->find($id);

        return new JsonResponse([
            'id' => $file->getId(),
            'filename' => $file->getFilename(),
        ]);
    }

    public function notify(Request $request)
    {
        return new JsonResponse();
    }


    public function uploadAction(Request $request): JsonResponse
    {
        $storedFiles = [];
        foreach($request->files as $file) {
            $uploadedFiles = is_array($file) ? $file : [$file];
            foreach ($uploadedFiles as $uploadedFile) {
                try {
                    /** @var $uploadedFile UploadedFile */
                    if ($uploadedFile->getError() != UPLOAD_ERR_OK) {
                        throw new UploadException('Error in file upload');
                    }
                    /** @var File $file */
                    $file = $this->getFileFactory()->createFromUploadedFile($uploadedFile);
                    $file->setGarbage(true);
                    $file->setContentType($this->getMediaLibraryManager()->matchContentType($file));
                    $this->getMediaManager()->saveFile($file);
                    $storedFiles[] = $file;

                } catch(StorageException $exception) {
                    foreach($storedFiles as $file) {
                        $this->getMediaManager()->deleteFile($file);
                    }
                }
            }
        }

        return $this->getFileResponse($storedFiles);
    }


    private function createTagList(): array
    {
        $terms = $this->getMediaLibraryManager()->getTags();
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
        $terms = $this->getMediaLibraryManager()->getContentTypes();
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
        $files = $this->getMediaLibraryManager()->getFiles($contentType, $tag);
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
