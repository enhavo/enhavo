<?php
/**
 * TableBlock.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block\Block;

use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class TableBlock extends AbstractType implements BlockInterface
{
    public function render($parameters)
    {
        return $this->renderTemplate('EnhavoAppBundle:Block:table.html.twig', $parameters);
    }

    public function getType()
    {
        return 'table';
    }
}