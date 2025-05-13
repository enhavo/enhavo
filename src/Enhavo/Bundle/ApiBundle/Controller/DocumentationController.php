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

use Enhavo\Bundle\ApiBundle\Documentation\DocumentationCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentationController extends AbstractController
{
    public function __construct(
        private DocumentationCollector $documentationCollector,
    ) {
    }

    public function indexAction(Request $request): Response
    {
        $section = $request->get('section', DocumentationCollector::DEFAULT);

        if (!$this->documentationCollector->hasSection($section)) {
            throw $this->createNotFoundException();
        }

        $data = $this->documentationCollector->collect($section);

        return $this->render('@EnhavoApi/docs.html.twig', [
            'data' => $data,
        ]);
    }
}
