<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ViewEndpointType extends AbstractEndpointType
{
    public function __construct(
        private Environment $twig,
        private TemplateResolver $templateResolver,
    ) {
    }

    public function getResponse($options, Request $request, Data $data, Context $context): Response
    {
        if ('json' === $request->get('_format')) {
            return $this->parent->getResponse($options, $request, $data, $context);
        } elseif (!$request->get('_format') || 'html' === $request->get('_format')) {
            if (null === $options['template'] && !$context->has('template')) {
                throw new \Exception('If format is html, then a template need to be provided over the configuration or context');
            }
            $template = $context->has('template') ? $context->get('template') : $options['template'];
            $content = $this->twig->render($this->templateResolver->resolve($template), $data->normalize());

            return $this->updateResponse(new Response($content), $context);
        }

        throw new NotFoundHttpException('No format found. Check your route configuration');
    }

    public static function getName(): ?string
    {
        return 'view';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => null,
        ]);
    }
}
