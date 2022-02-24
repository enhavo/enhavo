<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AbstractViewController;
use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\MediaLibraryBundle\Repository\FileRepository;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaLibraryController extends AbstractViewController
{
    /** @var MediaLibraryManager */
    private $mediaLibraryManager;



    public function __construct(ViewFactory $viewFactory, ViewHandlerInterface $viewHandler, MediaLibraryManager $mediaLibraryManager)
    {
        parent::__construct($viewFactory, $viewHandler);
        $this->mediaLibraryManager = $mediaLibraryManager;
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
     * @return JsonResponse
     */
    public function listAction(Request $request): JsonResponse
    {
        $items = $this->mediaLibraryManager->createItemList($request->get('tab'), $request->get('tag'));

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
        $tags = $this->mediaLibraryManager->createTagList();

        return new JsonResponse([
            'tags' => $tags,
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function addAction(Request $request)
    {
//        $id = $request->get('id');
//        $tab = $request->get('tab', 1);
//
//        return new JsonResponse([
//            'id' => $file->getId(),
//            'filename' => $file->getFilename(),
//        ]);
    }

    public function notify(Request $request)
    {
        return new JsonResponse();
    }


}
