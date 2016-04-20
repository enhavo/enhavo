<?php

namespace Enhavo\Bundle\SearchBundle\Block;

use Symfony\Component\Templating\EngineInterface;
use Enhavo\Bundle\AppBundle\Block\BlockInterface;

class SearchBlock implements BlockInterface
{
    protected $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function render($parameters)
    {
        return $this->engine->render('EnhavoSearchBundle:Block:search.html.twig', $parameters);
    }
}