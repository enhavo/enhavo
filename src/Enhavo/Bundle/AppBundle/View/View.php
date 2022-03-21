<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Component\Type\AbstractContainerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class View extends AbstractContainerType
{
    /** @var ViewTypeInterface */
    protected $type;

    /** @var ViewTypeInterface[] */
    protected $parents;

    public function getResponse(Request $request): Response
    {
        $viewData = new ViewData();
        foreach ($this->parents as $parent) {
            $parent->createViewData($this->options, $viewData);
        }
        $this->type->createViewData($this->options, $viewData);

        $templateData = new TemplateData();
        foreach ($this->parents as $parent) {
            $parent->createTemplateData($this->options, $viewData, $templateData);
        }
        $this->type->createTemplateData($this->options, $viewData, $templateData);

        $response = $this->type->handleRequest($this->options, $request, $viewData, $templateData);

        if ($response instanceof Response) {
            return $response;
        }

        return $this->type->getResponse($this->options, $request, $viewData, $templateData);
    }
}
