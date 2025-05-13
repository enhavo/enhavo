<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Index;

use Enhavo\Component\Type\TypeInterface;

interface IndexTypeInterface extends TypeInterface
{
    public function buildIndex(array $options, $model, IndexDataBuilder $builder): void;

    public function buildRawData(array $options, $model, IndexDataBuilder $builder): void;
}
