<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ContentBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SitemapEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly SitemapGenerator $sitemapGenerator,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        $response = new Response($this->sitemapGenerator->generate());
        $response->headers->set('Content-Type', 'text/xml');
        $context->setResponse($response);
    }
}
