<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-25
 * Time: 19:38
 */

namespace Enhavo\Bundle\TaxonomyBundle\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Filter\AbstractFilterType;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\TaxonomyBundle\Entity\Term;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaxonomyFilterType extends AbstractFilterType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TranslatorInterface $translator, EntityManagerInterface $em)
    {
        parent::__construct($translator);
        $this->em = $em;
    }

    public function createViewData($options, $name)
    {
        $choices = $this->getChoices($options);
        $data = parent::createViewData($options, $name);
        $data['value'] = null;
        $data['choices'] = $choices;
        return $data;
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if ($value == '') {
            return;
        }

        $property = $options['property'];
        $propertyPath = explode('.', $property);
        $query->addWhere('id', FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    private function getChoices($options)
    {
        $repository = $this->em->getRepository(Term::class);
        $entities = $repository->findByTaxonomyName($options['taxonomy']);
        $choices = [];
        foreach ($entities as $entity) {
            $choices[] = [
                'label' => $this->getProperty($entity, 'name'),
                'code' => $this->getProperty($entity, 'id')
            ];
        }
        return $choices;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setDefaults([
            'component' => 'filter-entity'
        ]);

        $optionsResolver->setRequired([
            'taxonomy'
        ]);

        $optionsResolver->remove('property');
    }

    public function getType()
    {
        return 'taxonomy';
    }
}
