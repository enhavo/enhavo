<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Batch;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface BatchTypeInterface extends TypeInterface
{
    /**
     * @param $options array
     * @param $resources
     * @return void
     */
    public function execute(array $options, $resources);

    /**
     * @param $options array
     * @return array
     */
    public function createViewData(array $options);

    /**
     * @param array $options
     * @return boolean
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
