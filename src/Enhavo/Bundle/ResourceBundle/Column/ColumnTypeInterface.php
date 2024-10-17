<?php

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnTypeInterface extends TypeInterface
{
    public function buildSortingQuery($options, FilterQuery $query, string $direction): void;

    public function createColumnViewData(array $options, Data $data): void;

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void;

    public function getPermission(array $options): mixed;

    public function isEnabled(array $options): bool;

    public function configureOptions(OptionsResolver $resolver);
}
