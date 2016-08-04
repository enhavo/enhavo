<?php

namespace Enhavo\Bundle\SearchBundle\Block;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\SearchBundle\Util\SearchUtil;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;

/*
 * get fields and entities to filter search
 */
class SearchBlock extends AbstractType implements BlockInterface
{
    protected $util;

    protected $metadataFactory;

    public function __construct(SearchUtil $util, MetadataFactory $metadataFactory)
    {
        $this->util = $util;
        $this->metadataFactory = $metadataFactory;
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

        //if there ar no entities set in dashboard routing get all possible entities
        if(!array_key_exists('entities', $parameters) || $parameters['entities'] == null){
            $entities = array();
            foreach ($searchYamls as $yamlPath) {
                $resultEntitiesWithTransDom = $this->getEntityNamesWithTransDom($yamlPath);
                foreach($resultEntitiesWithTransDom as $entity => $transDom){
                    $entities[strtolower($entity)]['label'] = lcfirst($entity).'.label.'.lcfirst($entity);
                    $entities[strtolower($entity)]['translationDomain'] = $transDom;
                }
            }
            $parameters['entities'] = $entities;
        }

        //if there ar no fields set in dashboard routing get all possible fields
        if(!array_key_exists('fields', $parameters) || $parameters['fields'] == null){
            $fields = array();
            foreach($searchYamls as $yamlPath){
                $yamlContent = $this->util->getContentOfSearchYaml($yamlPath);
                foreach($yamlContent as $key => $val){
                    $metadata = $this->metadataFactory->create($key);
                    foreach($metadata->getProperties() as $propertyNode){
                        if($propertyNode->getOptions() != null && array_key_exists('type', $propertyNode->getOptions()) && !in_array($propertyNode->getOptions()['type'], $fields)){
                            $fields[$propertyNode->getOptions()['type']]['label'] = lcfirst($metadata->getEntityName()).'.label.'.$propertyNode->getOptions()['type'];
                            $fields[$propertyNode->getOptions()['type']]['translationDomain'] = $metadata->getBundleName(); //Bundle
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
        //gets
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

    public function getEntityNamesWithTransDom($yamlPath)
    {
        //selects all entity names out of the given search yaml
        $yamlContent = $this->util->getContentOfSearchYaml($yamlPath);
        $entitiesAndBundle = array();
        foreach($yamlContent as $key => $value){
            $metadata = $this->metadataFactory->create($key);
            foreach ($metadata->getProperties() as $propertyNode) {
                if($propertyNode->getType() != 'Collection' && $propertyNode->getType() != 'Model')
                {
                    $entitiesAndBundle[$metadata->getEntityName()] = $metadata->getBundleName();
                }
            }
        }
        return $entitiesAndBundle;
    }
}