<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Index\Type;

use Enhavo\Bundle\SearchBundle\Index\IndexDataBuilder;
use Enhavo\Bundle\SearchBundle\Tests\Mock\ModelMock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlIndexTypeTest extends TestCase
{
    public function testGetIndexes()
    {
        $htmlIndexer = new HtmlIndexType();

        $model = new ModelMock();
        $model->setText('<p><h1>Header</h1><h2>Header2</h2>Lorem Ipsum dolor </p> Hallo');

        $builder = new IndexDataBuilder();
        $resolver = new OptionsResolver();

        $htmlIndexer->configureOptions($resolver);
        $options = $resolver->resolve(['property' => 'text']);

        $htmlIndexer->buildIndex($options, $model, $builder);

        $indexes = $builder->getIndex();

        $this->assertCount(4, $indexes);
        $index = $indexes[0];
        $this->assertEquals('Header', $index->getValue());
        $this->assertEquals(25, $index->getWeight());

        $index = $indexes[1];
        $this->assertEquals('Header2', $index->getValue());
        $this->assertEquals(18, $index->getWeight());

        $index = $indexes[2];
        $this->assertEquals('Lorem Ipsum dolor', $index->getValue());
        $this->assertEquals(1, $index->getWeight());

        $index = $indexes[3];
        $this->assertEquals('Hallo', $index->getValue());
        $this->assertEquals(1, $index->getWeight());
    }
}
