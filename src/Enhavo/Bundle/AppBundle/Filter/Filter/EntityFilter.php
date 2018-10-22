<?php
/**
 * TextFilter.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter\Filter;

use Enhavo\Bundle\AppBundle\Filter\AbstractFilter;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;

class EntityFilter extends AbstractFilter
{
    public function render($options, $value)
    {
        $repository = $this->getRequiredOption('repository', $options);
        $repository = $this->getRepository($repository);

        $path = $this->getOption('path', $options);

        $method = $this->getOption('method', $options, 'findAll');
        $arguments = $this->getOption('arguments', $options);
        if(is_array($arguments)) {
            $entities = call_user_func([$repository, $method], $arguments);
        } else {
            $entities = call_user_func([$repository, $method]);
        }

        $selectOptions = [];
        foreach ($entities as $entity) {
            if ($path) {
                $selectOptions[$this->getProperty($entity, 'id')] = $this->getProperty($entity, $path);
            } else {
                $selectOptions[$this->getProperty($entity, 'id')] = (string)$entity;
            }
        }

        $template = $this->getOption('template', $options, 'EnhavoAppBundle:Filter:option.html.twig');

        return $this->renderTemplate($template, [
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
        if ($value == '') {
            return;
        }

        $property = $this->getRequiredOption('property', $options);
        $propertyPath = explode('.', $property);
        $query->addWhere('id', FilterQuery::OPERATOR_EQUALS, $value, $propertyPath);
    }

    public function getRepository($repository)
    {
        if (preg_match('#[:/]#', $repository)) {
            return $this->container->get('doctrine.orm.entity_manager')->getRepository($repository);
        }
        return $this->container->get($repository);
    }

    public function getType()
    {
        return 'entity';
    }
}