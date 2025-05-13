<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
