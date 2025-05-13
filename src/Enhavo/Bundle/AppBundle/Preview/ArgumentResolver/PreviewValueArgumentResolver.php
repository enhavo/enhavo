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

use Enhavo\Bundle\AppBundle\Preview\PreviewManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PreviewValueArgumentResolver implements ArgumentValueResolverInterface
{
    /** @var PreviewManager */
    private $previewManager;

    /**
     * PreviewValueArgumentResolver constructor.
     */
    public function __construct(PreviewManager $previewManager)
    {
        $this->previewManager = $previewManager;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if ('preview' === $argument->getName() && 'bool' === $argument->getType()) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->previewManager->isPreview();
    }
}
