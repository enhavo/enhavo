<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:22
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
        $manager = new PrototypeManager(new TokenGeneratorMock, $csrfProtection);
        return new PreloadedExtension([new PolyCollectionType($manager)], []);
    }
}
