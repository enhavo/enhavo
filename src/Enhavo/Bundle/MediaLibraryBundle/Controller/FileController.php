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
use Enhavo\Bundle\MediaLibraryBundle\View\Type\MediaLibraryViewType;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class FileController extends ResourceController
{
    use FileControllerTrait;

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
        return $this->container->get('enhavo_media_library.factory.file');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var  $view */
        $view = $this->viewFactory->create([
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

        $view = $this->viewFactory->create([
            'type' => 'media_library',
            'multiple' => $request->get('multiple', false),
            'mode' => MediaLibraryViewType::MODE_SELECT,
            'request_configuration' => $configuration,
            'metadata' => $this->metadata,
        ]);
        return $view->getResponse($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function filesAction(Request $request): JsonResponse
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $columns = [
            ['property' => 'filename', 'label' => 'Name'],
            ['property' => 'extension', 'label' => 'Suffix'],
            ['property' => 'contentType', 'label' => 'Type'],
            ['property' => 'createdAt', 'label' => 'Date'],
        ];

        $page = $request->get('page', 1);
        $files = $this->createFileList($resources);
        $pages = range(1, $resources->getNbPages(), 1);

        return new JsonResponse([
            'files' => $files,
            'page' => $page,
            'pages' => $pages,
            'columns' => $columns,
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
                    $file->setGarbage(false);
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

    private function createFileList(Pagerfanta $files): array
    {
        $items = [];
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->get('enhavo_media.media.public_url_generator');
        foreach ($files as $file) {
            $items[] = [
                'id' => $file->getId(),
                'previewImageUrl' => $urlGenerator->generateFormat($file, 'enhavoMediaLibraryThumb'),
                'icon' => $this->getMediaLibraryManager()->getContentTypeIcon($file->getContentType()),
                'label' => $file->getFilename(),
            ];
        }

        return $items;
    }

}
