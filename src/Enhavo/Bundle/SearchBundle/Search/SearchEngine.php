<?php

namespace Enhavo\Bundle\SearchBundle\Search;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class SearchEngine implements SearchEngineInterface
{
    protected $container;

    protected $em;

    protected $util;

    public function __construct(Container $container, EntityManager $em, SearchUtil $util)
    {
        $this->container = $container;
        $this->em = $em;
        $this->util = $util;
    }

    /**
     * @param $query
     * @return array
     */
    public function search($query)
    {
        $request = new SearchRequest($this->container, $this->em, $this->util);

        $request->parseSearchExpression($query);

        if (!$request->hasToManyExpressions()) {

            $results = $request->search();
            $results = $this->checkUserPermission($results);
            if(empty($results)) {
                return [];
            }
            return $results;
        } else {
            throw new SearchEngineException('There are to many AND/OR expressions');
        }
    }

    protected function checkUserPermission($results)
    {
        $securityContext = $this->container->get('security.context');
        $resultResources = array();
        foreach ($results as $resource) {
            $public = true;

            //just return public resources
            if(method_exists($resource['object'], 'getPublic')){
                if(!$resource['object']->getPublic()){
                   $public = false;
                }
            }
            if($public){

                //check if user has the permission to see the resource
                $roleIndex = 'ROLE_'.strtoupper($resource['bundleName']).'_'.strtoupper($resource['entityName']).'_INDEX';
                if($securityContext->isGranted($roleIndex)){

                    //check if user has the permission to update the resource
                    $roleUpdate = 'ROLE_'.strtoupper($resource['bundleName']).'_'.strtoupper($resource['entityName']).'_UPDATE';
                    if($securityContext->isGranted($roleUpdate)){
                        $resource['update'] = true;
                    } else {
                        $resource['update'] = false;
                    }
                    $resultResources[] = $resource;
                }
            }
        }
        return $resultResources;
    }
}