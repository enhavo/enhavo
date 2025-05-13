<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form;

use Enhavo\Bundle\FormBundle\Form\Type\PolyCollectionType;
use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\FormBundle\Prototype\PrototypeManager;
use Symfony\Component\Form\PreloadedExtension;

class PreloadExtensionFactory
{
    public static function createWysiwygExtension()
    {
        $type = new WysiwygType('', '');

        return new PreloadedExtension([$type], []);
    }

    public static function createPolyCollectionTypeExtension($csrfProtection = false)
    {
        $manager = new PrototypeManager(new TokenGeneratorMock(), $csrfProtection);

        return new PreloadedExtension([new PolyCollectionType($manager)], []);
    }
}
