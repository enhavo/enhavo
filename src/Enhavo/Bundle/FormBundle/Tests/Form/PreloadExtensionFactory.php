<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:22
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Symfony\Component\Form\PreloadedExtension;

class PreloadExtensionFactory
{
    public static function createWysiwygExtension()
    {
        $type = new WysiwygType('', '', new EntrypointFileManagerMock());
        return new PreloadedExtension([$type], []);
    }
}
