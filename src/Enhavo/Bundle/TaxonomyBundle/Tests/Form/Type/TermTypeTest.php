<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-21
 * Time: 23:14
 */

namespace Enhavo\Bundle\TaxonomyBundle\Tests\Form\Type;

use Enhavo\Bundle\FormBundle\Tests\Form\PreloadExtensionFactory;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class TermTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Food',
            'text' => 'Food taste very good',
            'slug' => 'food',
        ];

        $term = new Term();
        $form = $this->factory->create(TermType::class, $term);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($term, $form->getData());
        $this->assertEquals('Food', $term->getName());
        $this->assertEquals('Food taste very good', $term->getText());
        $this->assertEquals('food', $term->getSlug());
    }

    public function getExtensions()
    {
        $type = new TermType(Term::class);
        return [
            new PreloadedExtension([$type,], []),
            PreloadExtensionFactory::createWysiwygExtension()
        ];
    }
}
