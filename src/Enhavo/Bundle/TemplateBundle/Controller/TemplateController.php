<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 21:01
 */

namespace Enhavo\Bundle\TemplateBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Template\TemplateManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TemplateController extends AbstractController
{
    use TemplateResolverTrait;

    /**
     * @var TemplateManager
     */
    private $templateManager;

    /**
     * TemplateController constructor.
     * @param TemplateManager $templateManager
     */
    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * @param Template $contentDocument
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showResourceAction($contentDocument, Request $request)
    {
        $template = $this->templateManager->getTemplate($contentDocument->getCode());
        $resource = $this->templateManager->getResource($template, $request);

        if(empty($resource)) {
            throw $this->createNotFoundException();
        }

        $this->templateManager->injectTemplate($contentDocument, $template);

        return $this->render($this->resolveTemplate($template->getTemplate()), [
            'template' => $contentDocument,
            'resource' => $resource
        ]);
    }
}
