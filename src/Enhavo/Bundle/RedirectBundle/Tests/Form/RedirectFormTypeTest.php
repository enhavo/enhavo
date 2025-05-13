<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RedirectBundle\Tests\Form;

use Enhavo\Bundle\RedirectBundle\Entity\Redirect;
use Enhavo\Bundle\RedirectBundle\Form\Type\RedirectType;
use Symfony\Component\Form\Test\TypeTestCase;

class RedirectFormTypeTest extends TypeTestCase
{
    protected function getTypes()
    {
        return [
            new RedirectType(Redirect::class),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'code' => '302',
            'from' => '/',
            'to' => '/index',
        ];

        $redirect = new Redirect();
        $form = $this->factory->create(RedirectType::class, $redirect);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($redirect, $form->getData());
        $this->assertEquals('302', $redirect->getCode(), 'After submitting code should be set');
        $this->assertEquals('/', $redirect->getFrom(), 'After submitting from should be set');
        $this->assertEquals('/index', $redirect->getTo(), 'After submitting to should be set');
    }
}
