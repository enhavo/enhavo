<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Controller;

use Enhavo\Bundle\ApiBundle\Documentation\DocumentationGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentationController extends AbstractController
{
    public function __construct(
        private DocumentationGenerator $documentationCollector,
    ) {
    }

    public function indexAction(Request $request): Response
    {
        $url = $this->generateUrl($request->attributes->get('data_route'));

        return $this->render('@EnhavoApi/docs.html.twig', [
            'url' => $url,
        ]);
    }

    public function dataAction(Request $request): Response
    {
        $section = $request->attributes->get('section', DocumentationGenerator::SECTION_DEFAULT);

        if (!$this->documentationCollector->hasSection($section)) {
            throw $this->createNotFoundException();
        }

        $data = $this->documentationCollector->generate($section);

        return new JsonResponse($data);
    }
}
