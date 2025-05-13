<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Init;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface InitInterface extends TypeInterface
{
    public function init(Output $io);
}
