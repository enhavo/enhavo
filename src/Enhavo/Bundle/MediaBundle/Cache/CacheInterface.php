<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Cache;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface CacheInterface
{
    public function invalid(FileInterface $file, $format);

    public function set(FileInterface $file, $format);

    public function refresh(FileInterface $file, $format);
}
