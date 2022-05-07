<?php
/**
 * ViewerInterface.php
 *
 * @since 20/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View;

use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ViewTypeInterface extends TypeInterface
{
    public function init($options);

    public function createViewData($options, ViewData $viewData);

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData);

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData);

    public function finishViewData($options, ViewData $data);

    public function finishTemplateData($options, ViewData $viewData, TemplateData $templateData);

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response;

    public function configureOptions(OptionsResolver $resolver);
}
