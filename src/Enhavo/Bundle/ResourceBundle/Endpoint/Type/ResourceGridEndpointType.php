<?php

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use Enhavo\Bundle\ResourceBundle\Grid\GridFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceGridEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly GridFactory $gridFactory,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Grid $grid */
        $grid = $this->gridFactory->create($options['grid']);
        $data->set('actions', $grid->getActionViewData(null, $options['actions']));
        $data->set('filters', $grid->getFiltersViewData($options['filters']));
        $data->set('columns', $grid->getColumnsViewData($options['columns']));
        $data->set('batches', $grid->getBatchesViewData($options['batches']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'actions' => [],
            'columns' => [],
            'filters' => [],
            'batches' => [],
        ]);

        $resolver->setRequired('grid');
    }

    public static function getName(): ?string
    {
        return 'resource_grid';
    }
}
