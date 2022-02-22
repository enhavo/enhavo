<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\AbstractViewController;
use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MediaLibraryController extends AbstractViewController
{

    public function __construct(ViewFactory $viewFactory, ViewHandlerInterface $viewHandler)
    {
        parent::__construct($viewFactory, $viewHandler);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function addAction(Request $request)
    {
//        $id = $request->get('id');
//        $tab = $request->get('tab', 1);
//
//        $file = $this->factory->createApiFile($id, $tab, null);
//
//        $file->setFileSize(strlen($file->getContent()->getContent())); // TODO required?
//        $this->getDoctrine()->getManager()->flush();
//
//        return new JsonResponse([
//            'id' => $file->getId(),
//            'token' => $file->getToken(),
//            'filename' => $file->getFilename(),
//            'extension' => $file->getExtension(),
//            'mimeType' => $file->getMimeType(),
//            'md5Checksum' => $file->getMd5Checksum()
//        ]);
    }

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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
//        $filter = $this->createFilter($request);
//        $items = $this->client->getList($filter);
//
//        return new JsonResponse([
//            'items' => $items
//        ]);
    }

    private function createCategoriesList($categories)
    {
//        $list = [];
//
//        /** @var Category $category */
//        foreach ($categories as $category){
//            $entry = [];
//            $entry['title'] = $category->getTitle();
//            $entry['MediaLibraryId'] = $category->getMediaLibraryId();
//            $entry['children'] = $this->createCategoriesList($category->getChildren());
//            $list[] = $entry;
//        }
//
//        return $list;
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
