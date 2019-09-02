<?php
/**
 * LocaleGenerator.php
 *
 * @since 11/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Route;

use Enhavo\Bundle\AppBundle\Routing\GeneratorInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;

interface LocaleGenerator extends GeneratorInterface
{
    /**
     * @param Routeable $routeable
     * @return string
     */
    public function generate(Routeable $routeable, $locale = null);
}