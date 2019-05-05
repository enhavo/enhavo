<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:14
 */

namespace Enhavo\Bundle\AppBundle\Widget;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
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

    public function getWidget($type, $options)
    {
        return $this->createWidget($type, $options);
    }

    /**
     * @param $options
     * @return Widget
     * @throws TypeMissingException
     */
    private function createWidget($type, $options)
    {
        if(!isset($options['type'])) {
            throw new TypeMissingException(sprintf('No type was given to create "%s"', Action::class));
        }

        /** @var WidgetTypeInterface $type */
        $type = $this->collector->getType($options['type']);
        unset($options['type']);
        $action = new Widget($type, $options);
        return $action;
    }
}
