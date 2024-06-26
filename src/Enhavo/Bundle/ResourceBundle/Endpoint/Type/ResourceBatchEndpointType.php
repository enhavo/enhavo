<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\View\ResourceMetadataHelperTrait;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Sylius\Bundle\ResourceBundle\Controller\ResourcesCollectionProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceBatchEndpointType extends AbstractEndpointType
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

    public function handleRequest($options, Request $request, Data $data, Context $context): void
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $optionsResolver->setRequired('request_configuration');
    }
}
