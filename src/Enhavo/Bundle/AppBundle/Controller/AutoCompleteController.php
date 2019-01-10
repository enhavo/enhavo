<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 05.09.18
 * Time: 18:18
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AutoCompleteController extends AbstractController
{
    public function searchAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);
        $entities = $this->getEntities($configuration, $request);

        $more = false;
        if($configuration->isPaginated() && $entities instanceof Pagerfanta) {
            $entities->setMaxPerPage($configuration->getLimit());
            $entities->setCurrentPage($configuration->getPage());
            $more = $entities->hasNextPage();
        }

        $results = $this->getResults($configuration, $entities);

        return new JsonResponse(array(
            'results' => $results,
            'more' => $more
        ));
    }

    private function getEntities(AutoCompleteConfiguration $configuration, Request $request)
    {
        $arguments = [];

        if($configuration->getRepositoryArguments()) {
            $arguments = $configuration->getRepositoryArguments();
            $expressionLanguage = $this->container->get('sylius.expression_language');
            foreach($arguments as &$argument) {
                $argument = $expressionLanguage->evaluate($argument, [
                    'request' => $request,
                    'configuration' => $configuration
                ]);
            }
        } else {
            $arguments[] = $configuration->getSearchTerm();
            $arguments[] = $configuration->getLimit();
        }

        $repository = $this->get('doctrine.orm.entity_manager')->getRepository($configuration->getClass());
        $result = call_user_func_array([$repository, $configuration->getRepositoryMethod()], $arguments);
        return $result;
    }

    private function getResults(AutoCompleteConfiguration $configuration, $entities)
    {
        $results = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($entities as $entity) {
            $id = $accessor->getValue($entity, 'id');
            if($configuration->getChoiceLabel()) {
                $text = $accessor->getValue($entity, $configuration->getChoiceLabel());
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

    private function createConfiguration(Request $request)
    {
        $configuration = new AutoCompleteConfiguration();

        $config =  $request->attributes->get('_config', []);
        if(!is_array($config)) {
            throw new \InvalidArgumentException('The config has to be an array');
        }

        if(!isset($config['class'])) {
            throw new \InvalidArgumentException('Class is missing');
        }
        $configuration->setClass($config['class']);

        if(!isset($config['repository']['method'])) {
            throw new \InvalidArgumentException('Repository method is missing');
        }
        $configuration->setRepositoryMethod($config['repository']['method']);


        if(isset($config['repository']['arguments'])) {
            $configuration->setRepositoryArguments($config['repository']['arguments']);
        }

        if(isset($config['paginated'])) {
            $configuration->setPaginated($config['paginated']);
        }

        if(isset($config['limit'])) {
            $configuration->setLimit($config['limit']);
        }

        if(isset($config['choice_label'])) {
            $configuration->setChoiceLabel($config['choice_label']);
        }

        $configuration->setPage($request->get('page', 1));
        $configuration->setSearchTerm($request->get('q', ''));

        return $configuration;
    }
}