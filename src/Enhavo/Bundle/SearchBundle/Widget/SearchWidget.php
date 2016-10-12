<?php
/**
 * SearchWidget.php
 *
 * @since 10/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;

class SearchWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'search';
    }

    public function render($options)
    {
        $template = 'EnhavoSearchBundle:Theme:Widget/search.html.twig';
        if(isset($options['template'])) {
            $template = $options['template'];
        }

        return $this->renderTemplate($template, [

        ]);
    }
}