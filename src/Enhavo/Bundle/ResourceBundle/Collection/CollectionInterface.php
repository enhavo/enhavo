<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Collection;

use Doctrine\ORM\EntityRepository;
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

    public function handleAction(string $action, array $payload): void;
}
