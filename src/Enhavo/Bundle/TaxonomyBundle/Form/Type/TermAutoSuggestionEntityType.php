<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TaxonomyBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\FormBundle\Form\Type\AutoSuggestEntityType;
use Enhavo\Bundle\TaxonomyBundle\Factory\TermFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermAutoSuggestionEntityType extends AbstractType
{
    /** @var string */
    private $dataClass;

    /** @var TermFactory */
    private $termFactory;

    public function __construct($dataClass, TermFactory $termFactory)
    {
        $this->dataClass = $dataClass;
        $this->termFactory = $termFactory;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => $this->dataClass,
            'property' => 'name',
        ]);

        $resolver->setNormalizer('query_builder', function (Options $options) {
            return function (EntityRepository $er) use ($options) {
                $qb = $er->createQueryBuilder('e');
                $qb->join('e.taxonomy', 't');
                $qb->andWhere('t.name = :name');
                $qb->setParameter('name', $options['taxonomy']);

                return $qb;
            };
        });

        $termFactory = $this->termFactory;
        $resolver->setNormalizer('factory', function (Options $options) use ($termFactory) {
            return function () use ($termFactory, $options) {
                return $termFactory->createNewOnTaxonomy($options['taxonomy']);
            };
        });

        $resolver->setRequired('taxonomy');
    }

    public function getParent()
    {
        return AutoSuggestEntityType::class;
    }
}
