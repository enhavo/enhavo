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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ErrorEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly TemplateResolver $templateResolver,
        private readonly Environment $twigEnvironment,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $data['status_code'] = $options['status_code'];
        $data['status_text'] = $options['status_text'];

        $template = $this->templateResolver->resolve(sprintf('theme/error/%s.html.twig', $options['status_code']));
        if ($this->twigEnvironment->getLoader()->exists($template)) {
            $context->set('template', $template);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template' => 'theme/error/default.html.twig',
            'exception' => null,
            'status_code' => null,
            'status_text' => null,
        ]);
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }
}
