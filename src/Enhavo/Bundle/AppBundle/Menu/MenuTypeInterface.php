<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface MenuTypeInterface extends TypeInterface
{
    public function getPermission(array $options): mixed;

    public function isEnabled(array $options): bool;

    public function createViewData(array $options, Data $data): void;
}
