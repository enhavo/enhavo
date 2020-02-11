<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-02-11
 * Time: 14:21
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Bundle\AppBundle\Exception\TypeMissingException;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\AppBundle\Widget\WidgetTypeInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ToolbarManager
{
    /**
     * @var TypeCollector
     */
    private $collector;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * WidgetManager constructor.
     * @param TypeCollector $collector
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(TypeCollector $collector, AuthorizationCheckerInterface $checker)
    {
        $this->collector = $collector;
        $this->checker = $checker;
    }

    public function createWidgetsViewData(array $configuration)
    {
        $data = [];
        $widgets = $this->getWidgets($configuration);
        foreach($widgets as $widget) {
            $data[] = $widget->createViewData();
        }
        return $data;
    }

    public function getWidgets(array $configuration)
    {
        $widgets = [];
        foreach($configuration as $name => $options) {
            $widget = $this->createWidget($name, $options);

            if($widget->isHidden()) {
                continue;
            }

            if($widget->getPermission() !== null && !$this->checker->isGranted($widget->getPermission())) {
                continue;
            }

            $widgets[$name] = $widget;
        }

        return $widgets;
    }

    /**
     * @param $options
     * @return Widget
     * @throws TypeMissingException
     */
    private function createWidget($name, $options)
    {
        if(!isset($options['type'])) {
            throw new TypeMissingException(sprintf('No type was given to create "%s"', Widget::class));
        }

        /** @var WidgetTypeInterface $type */
        $type = $this->collector->getType($options['type']);
        unset($options['type']);
        $widget = new Widget($name, $type, $options);
        return $widget;
    }
}
