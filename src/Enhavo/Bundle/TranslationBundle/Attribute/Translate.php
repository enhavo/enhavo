<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Translate
{
    public function __construct(
        public string $type,
        public array $options = [],
    ) {
    }
}
