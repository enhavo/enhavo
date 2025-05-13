<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Engine\SearchEngineInterface;
use Enhavo\Bundle\SearchBundle\Result\ResultConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResultConverter $resultConverter,
        private readonly SearchEngineInterface $searchEngine,
        private readonly ResourceExpressionLanguage $expressionLanguage,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $term = $request->get('q', '');
        $page = $request->get('page', 1);

        $filter = new Filter();
        $filter->setTerm($term);
        $filter->setLimit(100);
        $this->addFilters($filter, $options);

        $pagination = $this->searchEngine->searchPaginated($filter);

        $pagination->setMaxPerPage($options['max_per_page']);
        $pagination->setCurrentPage($page);

        $results = $this->resultConverter->convert($pagination, $term);

        $data->set('results', $results);
        $data->set('pagination', $pagination);
        $data->set('term', $term);
    }

    protected function addFilters(Filter $filter, $options): void
    {
        foreach ($options['filters'] as $filterConfiguration) {
            $resolver = new OptionsResolver();
            $resolver->setDefaults([
                'arguments' => [],
            ]);
            $resolver->setRequired(['key', 'class']);
            $options = $resolver->resolve($filterConfiguration);

            foreach ($options['arguments'] as &$argument) {
                if (str_starts_with($argument, 'expr:')) {
                    $argument = $this->expressionLanguage->evaluate(substr($argument, 5));
                }
            }

            $reflection = new \ReflectionClass($options['class']);
            $query = $reflection->newInstanceArgs($options['arguments']);
            $filter->addQuery($options['key'], $query);
        }
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'max_per_page' => 10,
            'filters' => [],
        ]);
    }
}
