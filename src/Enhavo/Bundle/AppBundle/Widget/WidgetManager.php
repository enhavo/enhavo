<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class WidgetManager
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * ActionManager constructor.
     */
    public function __construct(TypeCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @return Widget
     */
    public function getWidget($type, $options)
    {
        return $this->createWidget($type, $options);
    }

    private function createWidget(string $type, array $options): Widget
    {
        /** @var WidgetTypeInterface $type */
        $type = $this->collector->getType($type);
        $widget = new Widget($type, $options);

        return $widget;
    }
}
