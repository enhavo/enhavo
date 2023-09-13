<?php

namespace Enhavo\Bundle\AppBundle\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Endpoint\Data;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ViewEndpointType extends AbstractEndpointType
{
    public function __construct(
        private Environment $twig,
        private TemplateManager $templateManager,
    ) {}

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        if ($request->get('_format') === 'json') {
            return $this->parent->getResponse($options, $request, $data, $context);
        } else if ($request->get('_format') === 'html') {
            $content = $this->twig->render($this->templateManager->getTemplate($options['template']), $this->getTemplateData($options, $data));
            return new Response($content, $context->getStatusCode());
        }

        throw new NotFoundHttpException('No format found. Check your route configuration');
    }

    private function getTemplateData($options, Data $data): array
    {
        $templateData = $data->normalize();
        $templateData['entrypoint'] = $options['entrypoint'];
        return $templateData;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entrypoint' => null,
        ]);

        $resolver->setRequired('template');
    }
}
