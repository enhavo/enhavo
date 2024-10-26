<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\MediaBundle\Controller\FileControllerTrait;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;


class FileController extends AbstractController
{
    use FileControllerTrait;

    /**
     * @return MediaLibraryManager
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getMediaLibraryManager(): MediaLibraryManager
    {
        return $this->container->get('Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager');
    }

    /**
     * @return FileRepository
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getFileRepository(): FileRepository
    {
        return $this->container->get('enhavo_media.file.repository');
    }

    /**
     * @return TranslatorInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getTranslator(): TranslatorInterface
    {
        return $this->container->get('translator');
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
            'limit' => $configuration->getLimit(),
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
        $multiple = intval($request->get('multiple', 0));

        $view = $this->viewFactory->create([
            'type' => 'media_library',
            'limit' => $configuration->getLimit(),
            'multiple' => $multiple,
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
        /** @var RequestConfiguration $configuration */
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $page = $request->get('page', 1);
        $view = $this->viewFactory->create([
            'type'=> 'media_files',
            'page' => $page,
            'sorting' => $configuration->getSorting(),
            'request_configuration' => $configuration,
        ]);

        return $view->getResponse($request);
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
            'contentTypes' => $types,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function addAction(Request $request): JsonResponse
    {
        $id = $request->get('id');
        /** @var File $file */
        $file = $this->getFileRepository()->find($id);

        return new JsonResponse([
            'id' => $file->getId(),
            'filename' => $file->getFilename(),
            'basename' => $file->getBasename(),
            'mimeType' => $file->getMimeType(),
            'token' => $file->getToken(),
            'parameters' => $file->getParameters(),
        ]);
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
        foreach ($terms as $key => $term) {
            $contentTypes[] = [
                'key' => $key,
                'label' => $term,
            ];
        }
        return $contentTypes;
    }

    private function trans(string $key): string
    {
        return $this->getTranslator()->trans($key, [], 'EnhavoMediaLibraryBundle');
    }
}
