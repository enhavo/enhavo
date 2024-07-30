<?php

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;
use Pagerfanta\Pagerfanta;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface CollectionInterface
{
    public function setRepository(EntityRepository $repository);

    public function setFilters(array $filters);

    public function setColumns(array $columns);

    public function setRoutes(array $routes);

    public function setOptions(array $options): void;

    public function configureOptions(OptionsResolver $resolver): void;

    public function getItems(array $context = []): ResourceItems;

    public function getViewData(array $context = []): array;
}
