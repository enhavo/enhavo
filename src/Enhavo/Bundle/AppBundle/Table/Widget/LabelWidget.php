<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 04/09/16
 * Time: 10:43
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class LabelWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $label = $this->getProperty($item, $options['property']);

        $translationDomain = null;
        if(isset($options['translationDomain'])) {
            $translationDomain = $options['translationDomain'];
        }
        if(isset($options['translationDomainProperty'])) {
            $translationDomain = $this->getProperty($item, $options['translationDomainProperty']);
        }

        return $this->container->get('translator')->trans($label, [], $translationDomain);
    }

    public function getType()
    {
        return 'label';
    }
}