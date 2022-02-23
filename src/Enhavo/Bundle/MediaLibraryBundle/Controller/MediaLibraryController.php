<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AbstractViewController;
use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaLibraryController extends AbstractViewController
{

    public function __construct(ViewFactory $viewFactory, ViewHandlerInterface $viewHandler)
    {
        parent::__construct($viewFactory, $viewHandler);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $view = $this->viewFactory->create('media_library', [
        ]);
        return $this->viewHandler->handle($view);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request): JsonResponse
    {
//        $filter = $this->createFilter($request);
//        $items = $this->repository->getList($filter);
//
        return new JsonResponse([
            'items' => [
                ['id' => 1, 'previewImageUrl' => '', 'name' => '1 bild']
            ]
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function tagsAction(Request $request): JsonResponse
    {
//        $filter = $this->createFilter($request);
//        $items = $this->repository->getTags($filter);
//
        return new JsonResponse([
            'tags' => [
                ['title' => 'mein erste tag']
            ]
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

    /**
     * @param Request $request
     * @return void
     */
    public function showAction(Request $request)
    {
//        $filter = $this->createFilter($request);
//        $items = $this->client->getList($filter);
//
//        $em = $this->getDoctrine()->getManager();
//
//        $categories = $em->getRepository(Category::class)->findBy([
//            'parent' => null
//        ]);
//
//        $parameters = [
//            'items' => $items,
//            'categories' => $this->createCategoriesList($categories),
//            'data' => [
//                'items' => $items
//            ],
//            'multiple' => $request->get('multiple', '1') === '1',
//            'label' => 'MediaLibrary'
//        ];
//
//        $view = $this->viewFactory->create('MediaLibrary', $parameters);
//
//        return $this->viewHandler->handle($view);
    }

    private function createFilter(Request $request)
    {
//        $filter = new Filter();
//
//        if(!$request->query->has('tab')) {
//            $this->createNotFoundException('Tab is necessary');
//        } else {
//            $filter->setTab($request->query->get('tab'));
//        }
//
//        if($request->query->has('limit')) {
//            $filter->setLimit($request->query->get('limit'));
//        }
//
//        if($request->query->has('search')) {
//            $filter->setSearch($request->query->get('search'));
//        }
//
//        if($request->query->has('offset')) {
//            $filter->setOffset($request->query->get('offset'));
//        }
//
//        if($request->query->has('category')) {
//            $filter->setCategory($request->query->get('category'));
//        }
//
//        return $filter;
    }

    public function notify(Request $request)
    {
        return new JsonResponse();
    }
}
