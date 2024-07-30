<?php

namespace Enhavo\Bundle\ResourceBundle\Grid;

use Enhavo\Bundle\ResourceBundle\Collection\ResourceItem;
use Enhavo\Bundle\ResourceBundle\Collection\ResourceItems;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface GridInterface
{
    public function setOptions($options): void;

    public function configureOptions(OptionsResolver $resolver): void;

    public function getViewData(array $context = []): array;

    public function getItems(array $context = []): ResourceItems;
}
