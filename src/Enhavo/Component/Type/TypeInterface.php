<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface TypeInterface
{
    /**
     * Returns a unique name for this type
     */
    public static function getName(): ?string;

    /**
     * Returns the parent type
     */
    public static function getParentType(): ?string;

    /**
     * @param $parent TypeInterface
     */
    public function setParent(TypeInterface $parent);

    public function configureOptions(OptionsResolver $resolver);
}
