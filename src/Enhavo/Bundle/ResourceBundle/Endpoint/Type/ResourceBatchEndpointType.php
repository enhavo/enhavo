<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Batch\BatchManager;
use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use Enhavo\Bundle\ResourceBundle\Grid\GridFactory;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceBatchEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly GridFactory $gridFactory,
    ) {}

    public static function getName(): ?string
    {
        return 'resource_batch';
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Grid $grid */
        $grid = $this->gridFactory->create($options['grid']);
        $batch = $grid->getBatch($request->get('type'));

        if ($batch === null) {
            throw $this->createNotFoundException();
        }

        $repository = $this->resourceManager->getRepository($grid->getResourceName());

        $ids = $request->get('ids');
        $batch->execute($ids, $repository, $data, $context);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('grid');
    }
}
