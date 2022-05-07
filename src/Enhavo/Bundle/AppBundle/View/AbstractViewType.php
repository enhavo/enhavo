<?php

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Bundle\AppBundle\View\Type\BaseViewType;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractViewType extends AbstractType implements ViewTypeInterface
{
    /** @var $parent self */
    protected $parent;

    public function init($options)
    {

    }

    public function createViewData($options, ViewData $data)
    {

    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {

    }

    public function finishViewData($options, ViewData $viewData)
    {

    }

    public function finishTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {

    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        return $this->parent->handleRequest($options, $request, $viewData, $templateData);
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        return $this->parent->getResponse($options, $request, $viewData, $templateData);
    }

    public static function getParentType(): ?string
    {
        return BaseViewType::class;
    }
}
