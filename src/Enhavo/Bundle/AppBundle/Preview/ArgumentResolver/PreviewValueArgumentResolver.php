<?php

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
     * @param PreviewManager $previewManager
     */
    public function __construct(PreviewManager $previewManager)
    {
        $this->previewManager = $previewManager;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if ($argument->getName() === 'preview' && $argument->getType() === 'bool') {
            return true;
        }
        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->previewManager->isPreview();
    }
}
