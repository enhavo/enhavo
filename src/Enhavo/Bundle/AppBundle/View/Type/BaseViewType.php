<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\AppBundle\View\ViewTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseViewType extends AbstractType implements ViewTypeInterface
{
    public static function getName(): ?string
    {
        return 'base';
    }

    public function createViewData($options, ViewData $viewData)
    {

    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {

    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {

    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        return new Response();
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {

    }
}
