<?php

namespace Enhavo\Bundle\AppBundle\Preview\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ContentDocumentValueArgumentResolver
 *
 * This class resolve the contentDocument value to a placeholder, because the CMFRoutingBundle handle the injection of the contentDocument
 * via its enhancers. To avoid Exceptions during the default symfony argument resolver, we replace it by putting a placeholder as value
 *
 * @package Enhavo\Bundle\AppBundle\Preview\ArgumentResolver
 */
class ContentDocumentValueArgumentResolver implements ArgumentValueResolverInterface
{
    const PLACEHOLDER = '__CONTENT_DOCUMENT_PLACEHOLDER__';

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getName() === 'contentDocument') {
            return true;
        }
        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield '__CONTENT_DOCUMENT_PLACEHOLDER__';
    }
}
