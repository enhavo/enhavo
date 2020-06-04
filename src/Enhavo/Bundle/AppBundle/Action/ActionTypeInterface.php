<?php
/**
 * ActionInterface.php
 *
 * @since 31/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ActionTypeInterface extends TypeInterface
{
    /**
     * Feed a action with parameter and you it will
     * return a string. Normally html.
     *
     * @param $options array
     * @param $resource object|null
     * @return array
     */
    public function createViewData(array $options, $resource = null);

    /**
     * @param array $options
     * @param object $resource
     * @return mixed
     */
    public function getPermission(array $options, $resource = null);

    /**
     * @param array $options
     * @param object $resource
     * @return boolean
     */
    public function isHidden(array $options, $resource = null);

    /**
     * @param $resolver OptionsResolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
