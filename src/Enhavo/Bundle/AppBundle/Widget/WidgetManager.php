<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
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
     * @param TypeCollector $collector
     */
    public function __construct(TypeCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * @param $type
     * @param $options
     * @return Widget
     */
    public function getWidget($type, $options)
    {
        return $this->createWidget($type, $options);
    }

    /**
     * @param string $type
     * @param array $options
     * @return Widget
     */
    private function createWidget(string $type, array $options): Widget
    {
        /** @var WidgetTypeInterface $type */
        $type = $this->collector->getType($type);
        $widget = new Widget($type, $options);
        return $widget;
    }
}
