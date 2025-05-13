<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\GarbageCollection;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

interface GarbageCollectorInterface
{
    /**
     * Run the garbage collection process.
     *
     * @param int|null $maxItems Maximum items to process. Null to use default value (enhavo_media.garbage_collection.max_items_per_run). 0 or lower for unlimited.
     * @param bool     $andFlush Call EntityManager->flush() after processing (default true)
     */
    public function run(?int $maxItems = null, bool $andFlush = true): void;

    /**
     * Run the garbage collection test on a specific file only.
     *
     * @param FileInterface $file     the file to run garbage collection on
     * @param bool          $andFlush Call EntityManager->flush() after processing (default true)
     */
    public function runOnFile(FileInterface $file, bool $andFlush = true): void;
}
