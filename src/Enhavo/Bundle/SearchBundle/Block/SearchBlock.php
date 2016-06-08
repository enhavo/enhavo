<?php

namespace Enhavo\Bundle\SearchBundle\Block;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Block\BlockInterface;

class SearchBlock extends AbstractType implements BlockInterface
{
    public function render($parameters)
    {
        if(!is_array($parameters))
        {
            $parameters = array();
        }
        return $this->renderTemplate('EnhavoSearchBundle:Block:search.html.twig', $parameters);
    }

    public function getType()
    {
        return 'search';
    }
}