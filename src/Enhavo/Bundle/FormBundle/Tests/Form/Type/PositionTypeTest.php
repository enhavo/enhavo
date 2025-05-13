<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\PositionType;
use Symfony\Component\Form\Test\TypeTestCase;

class PositionTypeTest extends TypeTestCase
{
    protected function createForm($options = [])
    {
        $form = $this->factory->create(PositionType::class, null, $options);

        return $form;
    }

    public function testSubmitValidData()
    {
        $form = $this->createForm();

        $form->setData('');
        $form->submit('1');
        $form->createView();

        $this->assertTrue($form->isSynchronized());
    }
}
