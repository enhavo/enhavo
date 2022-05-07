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
        $templateData = new TemplateData();

        foreach ($this->parents as $parent) {
            $parent->init($this->options);
        }
        $this->type->init($this->options);

        foreach ($this->parents as $parent) {
            $parent->createViewData($this->options, $viewData);
        }
        $this->type->createViewData($this->options, $viewData);

        foreach ($this->parents as $parent) {
            $parent->createTemplateData($this->options, $viewData, $templateData);
        }
        $this->type->createTemplateData($this->options, $viewData, $templateData);

        $response = $this->type->handleRequest($this->options, $request, $viewData, $templateData);
        if ($response instanceof Response) {
            return $response;
        }

        foreach ($this->parents as $parent) {
            $parent->finishViewData($this->options, $viewData);
        }
        $this->type->finishViewData($this->options, $viewData);

        foreach ($this->parents as $parent) {
            $parent->finishTemplateData($this->options, $viewData, $templateData);
        }
        $this->type->finishTemplateData($this->options, $viewData, $templateData);

        return $this->type->getResponse($this->options, $request, $viewData, $templateData);
    }
}
