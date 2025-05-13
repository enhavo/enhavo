<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Serializer;

use Symfony\Component\Form\FormInterface;

interface SerializerInterface
{
    public function serialize(FormInterface $form, $format): mixed;
}
