<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface ActionTypeInterface extends TypeInterface
{
    public function createViewData(array $options, Data $data, ?object $resource = null): void;

    public function getPermission(array $options, ?object $resource = null): mixed;

    public function isEnabled(array $options, ?object $resource = null): bool;

    public function getLabel(array $options): string;
}
