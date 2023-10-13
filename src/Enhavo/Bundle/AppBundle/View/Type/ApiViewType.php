<?php

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ApiViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private Environment $twig,
        private TemplateResolver $templateResolver,
    ) {}

    public static function getName(): ?string
    {
        return 'api';
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        if ($request->isXmlHttpRequest() || $request->get('_format') === 'json') {
            return new JsonResponse($viewData->normalize());
        }

        $content = $this->twig->render($this->templateResolver->resolve($templateData->getTemplate()), $templateData->normalize());

        return new Response($content);
    }

    public function createTemplateData($options, ViewData $viewData, TemplateData $templateData)
    {
        if (isset($options['request_configuration'])) {
            $configuration = $this->getRequestConfiguration($options);
            $template = $configuration->getTemplate($options['template']);
        } else {
            $template = $options['template'];
        }

        $templateData->setTemplate($template);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'entrypoint' => null,
        ]);

        $optionsResolver->setRequired('template');
    }
}
