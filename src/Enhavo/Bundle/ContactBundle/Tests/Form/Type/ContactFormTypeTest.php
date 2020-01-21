<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 16:44
 */

namespace Enhavo\Bundle\ContactBundle\Tests\Form\Type;

use Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType;
use Enhavo\Bundle\ContactBundle\Model\Contact;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

class ContactFormTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $type = new ContactFormType(Contact::class);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'peter@pan.de',
            'firstName' => 'Peter',
            'lastName' => 'Pan',
            'message' => 'Where am i',
        ];

        $contact = new Contact();
        $form = $this->factory->create(ContactFormType::class, $contact);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($contact, $form->getData());
        $this->assertEquals('peter@pan.de', $contact->getEmail(), 'After submitting email should be set');
        $this->assertEquals('Peter', $contact->getFirstName(), 'After submitting first name should be set');
        $this->assertEquals('Pan', $contact->getLastName(), 'After submitting last name should be set');
        $this->assertEquals('Where am i', $contact->getMessage(), 'After submitting message should be set');
    }
}
