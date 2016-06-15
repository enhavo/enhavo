<?php

namespace Enhavo\Bundle\SearchBundle\Block;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Symfony\Component\Yaml\Parser;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;

class SearchBlock extends AbstractType implements BlockInterface
{
    protected $util;

    public function __construct(SearchUtil $util)
    {
        $this->util = $util;
    }

    public function render($parameters)
    {
        if(!is_array($parameters))
        {
            $parameters = array();
        }
        $parameters = $this->getEntitiesAndFields($parameters);
        return $this->renderTemplate('EnhavoSearchBundle:Block:search.html.twig', $parameters);
    }

    public function getType()
    {
        return 'search';
    }

    protected function getEntitiesAndFields($parameters){
        $searchYamls = $this->util->getSearchYamls();
        if(!array_key_exists('entities', $parameters) || $parameters['entities'] == null){
            $entities = array();
            foreach ($searchYamls as $yamlPath) {
                $resultEntities = $this->util->getEntityNamesOfSearchYamlPath($yamlPath);
                foreach($resultEntities as $entity){
                    $entities[strtolower($entity)]['label'] = lcfirst($entity).'.label.'.lcfirst($entity);
                    $entities[strtolower($entity)]['translationDomain'] = $this->getTranslationDomain($yamlPath);

                }
            }
            $parameters['entities'] = $entities;
        }

        if(!array_key_exists('fields', $parameters) || $parameters['fields'] == null){
            $fields = array();
            foreach($searchYamls as $yamlPath){
                $yamlContent = $this->util->getContentOfSearchYaml($yamlPath);
                foreach($yamlContent as $key => $val){
                    $currentTypes = $this->util->getTypes($yamlContent, $key);
                    foreach($currentTypes as $type){
                        if(!in_array($type, $fields)){
                            $splittedKey = explode('\\', $key);
                            $entityName = lcfirst(array_pop($splittedKey));
                            $fields[$type]['label'] = $entityName.'.label.'.$type;
                            $fields[$type]['translationDomain'] = $this->getTranslationDomain($yamlPath);
                        }
                    }
                }
            }
           $parameters['fields'] = $fields;
        }
        return $parameters;
    }

    protected function getTranslationDomain($yamlPath)
    {
        $splittedYamlPath = explode('/', $yamlPath);
        while(end($splittedYamlPath) != 'Resources'){
            array_pop($splittedYamlPath);
        }
        $transDomPath = implode('/', $splittedYamlPath).'/translations';
        $transDomains = scandir($transDomPath);
        foreach($transDomains as $domain){
            if($domain != '.' && $domain != '..'){
                $splittedDom = explode('.', $domain);
                return $splittedDom[0];
            }
        }
    }
}