<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\FilterInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class TextFilter extends AbstractType implements FilterInterface
{
    public function render($options, $value)
    {
        return $this->renderTemplate('EnhavoAppBundle:Filter:text.html.twig', [
            'type' => $this->getType(),
            'value' => $value,
            'label' => $this->getOption('label', $options, ''),
            'translationDomain' => $this->getOption('translationDomain', $options, null),
            'icon' => $this->getOption('icon', $options, ''),
        ]);
    }

    public function getType()
    {
        return 'text';
    }
}