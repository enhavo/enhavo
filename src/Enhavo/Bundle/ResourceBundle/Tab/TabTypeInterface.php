<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Tab;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Component\Type\TypeInterface;

interface TabTypeInterface extends TypeInterface
{
    public function createViewData(array $options, Data $data, InputInterface $input): void;

    public function getPermission(array $options, InputInterface $input): mixed;

    public function isEnabled(array $options, InputInterface $input): bool;
}
