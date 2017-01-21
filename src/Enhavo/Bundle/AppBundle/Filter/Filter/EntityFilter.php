<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\FilterInterface;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Exception\FilterException;
use Doctrine\ORM\Query;

class EntityFilter extends AbstractType implements FilterInterface
{
    public function render($options, $value)
    {
        $repository = $this->getRequiredOption('repository', $options);
        $repository = $this->getRepository($repository);

        $path = $this->getOption('path', $options);

        $entities = $repository->findAll();
        $selectOptions = [];
        foreach($entities as $entity) {
            if($path) {
                $selectOptions[$this->getProperty($entity, 'id')] = $this->getProperty($entity, $path);
            } else {
                $selectOptions[$this->getProperty($entity, 'id')] = (string)$entity;
            }
        }

        return $this->renderTemplate('EnhavoAppBundle:Filter:option.html.twig', [
            'type' => $this->getType(),
            'value' => $value,
            'label' => $this->getOption('label', $options, ''),
            'translationDomain' => $this->getOption('translationDomain', $options, null),
            'icon' => $this->getOption('icon', $options, ''),
            'options' => $selectOptions,
            'name' => $this->getRequiredOption('name', $options),
        ]);
    }

    public function buildQuery(FilterQuery $query, $options, $value)
    {
        if($value == '') {
            return;
        }
        
        $property = $this->getRequiredOption('property', $options);
        $query->addWhere($property, FilterQuery::OPERATOR_EQUALS, $value, 'id');
    }

    public function getRepository($repository)
    {
        if(preg_match('#[:/]#', $repository)) {
            return $this->container->get('doctrine.orm.entity_manager')->getRepository($repository);
        }
        return $this->container->get($repository);
    }

    public function getType()
    {
        return 'entity';
    }
}