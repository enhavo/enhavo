<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.12.18
 * Time: 23:12
 */

namespace Enhavo\Bundle\FormBundle\Serializer;

use Symfony\Component\Form\FormInterface;

interface SerializerInterface
{
    public function serialize(FormInterface $form, $format): mixed;
}