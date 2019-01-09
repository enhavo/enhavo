<?php
/**
 * ViewerInterface.php
 *
 * @since 20/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ViewerInterface extends TypeInterface
{
    public function createView($options): View;

    public function configureOptions(OptionsResolver $optionsResolver);
}