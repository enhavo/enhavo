<?php
/**
 * TableBlock.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block;

use Symfony\Component\Templating\EngineInterface;

class TableBlock implements BlockInterface
{
    protected $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function render($parameters)
    {
        return $this->engine->render('EnhavoAppBundle:Block:table.html.twig', $parameters);
    }
}