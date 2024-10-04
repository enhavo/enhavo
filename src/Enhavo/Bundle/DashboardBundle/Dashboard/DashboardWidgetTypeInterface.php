<?php


namespace Enhavo\Bundle\DashboardBundle\Dashboard;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface DashboardWidgetTypeInterface extends TypeInterface
{
    public function createViewData(array $options, Data $data): void;

    public function getPermission(array $options): mixed;

    public function isEnabled(array $options): bool;
}
