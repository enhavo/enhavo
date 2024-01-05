<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 07.03.18
 * Time: 23:26
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem;

use Enhavo\Component\Type\TypeInterface;

interface NavItemTypeInterface extends TypeInterface
{
    public function getModel($options);

    public function getForm($options);

    public function getFormOptions($options);

    public function getLabel(array $options);

    public function getTranslationDomain(array $options);

    public function getFactory($options);

    public function getTemplate($options);

    public function getComponent($options);

    public function render($options);
}
