<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Template\TemplateManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TemplateController extends AbstractController
{
    use TemplateResolverTrait;

    /**
     * @var TemplateManager
     */
    private $templateManager;

    /**
     * TemplateController constructor.
     */
    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * @param Template $contentDocument
     *
     * @return Response
     */
    public function showResourceAction($contentDocument, Request $request)
    {
        $template = $this->templateManager->getTemplate($contentDocument->getCode());
        $resource = $this->templateManager->getResource($template, $request);

        if (empty($resource)) {
            throw $this->createNotFoundException();
        }

        $this->templateManager->injectTemplate($contentDocument, $template);

        return $this->render($this->resolveTemplate($template->getTemplate()), [
            'template' => $contentDocument,
            'resource' => $resource,
        ]);
    }
}
