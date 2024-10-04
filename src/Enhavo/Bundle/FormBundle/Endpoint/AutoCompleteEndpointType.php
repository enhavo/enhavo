<?php

namespace Enhavo\Bundle\FormBundle\Endpoint;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AutoCompleteEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly ResourceExpressionLanguage $resourceExpressionLanguage,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        $term = $request->get('q', $options['term_key']);
        $page = $request->get('page', 1);

        $entities = $this->getEntities($options, $request, $term);

        $more = false;
        if ($options['paginated'] && $entities instanceof Pagerfanta) {
            $entities->setMaxPerPage($options['limit']);
            $entities->setCurrentPage($page);
            $more = $entities->hasNextPage();
        }

        $results = $this->getResults($options, $entities);

        $data->set('results', $results);
        $data->set('more', $more);
    }

    private function getEntities(array $options, Request $request, string $term)
    {
        $arguments = [];

        if (count($options['repository_arguments']) > 0) {
            $arguments = $options['repository_arguments'];
            foreach ($arguments as $key => $argument) {
                $arguments[$key] = $this->resourceExpressionLanguage->evaluate($argument, [
                    'request' => $request,
                    'options' => $options
                ]);
            }
        } else {
            $arguments[] = $term;
            $arguments[] = $options['limit'];
        }

        $repository = $this->em->getRepository($options['class']);
        return call_user_func_array([$repository, $options['repository_method']], $arguments);
    }

    private function getResults(array $options, array|Pagerfanta $entities): array
    {
        $results = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($entities as $entity) {
            if ($options['id_property']) {
                $id = $accessor->getValue($entity, $options['id_property']);
            } else {
                $id = $accessor->getValue($entity, 'id');
            }
            if ($options['choice_label']) {
                $text = $accessor->getValue($entity, $options['choice_label']);
            } else {
                $text = (string)$entity;
            }

            $results[] = [
                'id' => $id,
                'text' => $text
            ];
        }
        return $results;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'repository_arguments' => [],
            'paginated' => false,
            'limit' => 10,
            'choice_label' => null,
            'id_property' => null,
            'term_key' => 'q',
        ]);

        $resolver->setRequired(['class', 'repository_method']);
    }

    public static function getName(): ?string
    {
        return 'auto_complete';
    }
}
