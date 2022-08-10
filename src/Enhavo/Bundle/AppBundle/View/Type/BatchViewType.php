<?php

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Batch\BatchManager;
use Enhavo\Bundle\AppBundle\Exception\BatchExecutionException;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchViewType extends AbstractViewType
{
    use ResourceMetadataHelperTrait;

    public function __construct(
        private ResourceManager $resourceManager,
        private BatchManager $batchManager,
        private ResourcesCollectionProvider $resourcesCollectionProvider,
    ) {}

    public static function getName(): ?string
    {
        return 'batch';
    }

    public function handleRequest($options, Request $request, ViewData $viewData, TemplateData $templateData)
    {
        $configuration = $this->getRequestConfiguration($options);
        $metadata = $this->getMetadata($options);

        $repository = $this->resourceManager->getRepository($metadata->getApplicationName(), $metadata->getName());
        $resources = $this->resourcesCollectionProvider->get($configuration, $repository);

        $batchConfiguration = $configuration->getBatches();
        $type = $configuration->getBatchType();

        $batch = $this->batchManager->getBatch($type, $batchConfiguration);
        if($batch === null) {
            throw new NotFoundHttpException();
        }

        try {
            $response = $this->batchManager->executeBatch($batch, $resources);
            if ($response !== null) {
                return $response;
            }
        } catch (BatchExecutionException $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        return new JsonResponse();
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setRequired('request_configuration');
    }
}
