<?php
/**
 * ToolbarWidgetTypeInterface.php
 *
 * @since 11/02/20
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ToolbarWidgetTypeInterface extends TypeInterface
{
    /**
     * @param $name string
     * @param $options array
     * @return string
     */
    public function createViewData($name, array $options);

    /**
     * @param array $options
     * @return string
     */
    public function getPermission(array $options);

    /**
     * @param array $options
     * @return boolean
     */
    public function isHidden(array $options);

    /**
     * @param $resolver OptionsResolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
