<?php
/**
 * MenuBuilderInterface.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface MenuInterface extends TypeInterface
{
    public function getPermission(array $options);

    public function isHidden(array $options);

    public function isActive(array $options);

    public function createViewData(array $options);

    public function configureOptions(OptionsResolver $resolver);
}