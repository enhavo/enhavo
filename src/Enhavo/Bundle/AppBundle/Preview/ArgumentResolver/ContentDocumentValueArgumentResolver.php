<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Preview\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class ContentDocumentValueArgumentResolver
 *
 * This class resolve the contentDocument value to a placeholder, because the CMFRoutingBundle handle the injection of the contentDocument
 * via its enhancers. To avoid Exceptions during the default symfony argument resolver, we replace it by putting a placeholder as value
 */
class ContentDocumentValueArgumentResolver implements ArgumentValueResolverInterface
{
    public const PLACEHOLDER = '__CONTENT_DOCUMENT_PLACEHOLDER__';

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ('contentDocument' === $argument->getName()) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield '__CONTENT_DOCUMENT_PLACEHOLDER__';
    }
}
