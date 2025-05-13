<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;

class DeleteListener
{
    public function __construct(
        private readonly SearchEngineInterface $searchEngine,
    ) {
    }

    public function onDelete(ResourceEvent $event)
    {
        $this->searchEngine->removeIndex($event->getSubject());
    }
}
