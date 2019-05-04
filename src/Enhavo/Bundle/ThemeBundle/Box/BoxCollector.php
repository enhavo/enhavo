<?php
/**
 * BoxCollector.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\Box;


class BoxCollector
{
    /**
     * @var array
     */
    protected $boxes;

    public function __construct($boxes)
    {
        $this->boxes = $boxes;
    }

    public function getWidgets($box)
    {
        $config = $this->getBox($box);
        if(isset($config['widgets'])) {
            return $config['widgets'];
        }
        return [];
    }

    public function getTemplate($box)
    {
        $config = $this->getBox($box);
        if(isset($config['template'])) {
            return $config['template'];
        }
        throw new \InvalidArgumentException(
            sprintf('Box template for box "%s" was not defined. Please chek your configuration under enhavo_theme.boxes.%s', $box, $box)
        );
    }

    protected function getBox($box)
    {
        if(isset($this->boxes[$box])) {
            return $this->boxes[$box];
        }
        throw new \InvalidArgumentException(sprintf('Box "%s" does not exists', $box));
    }
}