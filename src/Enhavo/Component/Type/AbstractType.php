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

class AbstractType implements TypeInterface
{
    /** @var self|null */
    protected $parent;

    public function setParent(TypeInterface $parent)
    {
        $this->parent = $parent;
    }

    public static function getParentType(): ?string
    {
        return null;
    }

    public static function getName(): ?string
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
