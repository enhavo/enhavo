<?php
/**
 * TableBlock.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Block;

use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class LibraryBlock extends AbstractType implements BlockInterface
{
    public function render($parameters)
    {
        $template = $this->getOption('template', $parameters, 'EnhavoMediaBundle:Block:library.html.twig');

        return $this->renderTemplate($template, [

        ]);
    }

    public function getType()
    {
        return 'media_library';
    }
}