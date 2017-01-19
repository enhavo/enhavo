<?php

namespace Enhavo\Bundle\CategoryBundle\Action;

use Enhavo\Bundle\AppBundle\Action\ActionInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class CategoryAction extends AbstractType implements ActionInterface
{
    public function render($parameters)
    {
        $collection = $this->container->getParameter('enhavo_category.default_collection');
        if(isset($parameters['collection'])) {
            $collection = $parameters['collection'];
        }

        return $this->renderTemplate('EnhavoAppBundle:Action:default.html.twig', [
            'type' => $this->getType(),
            'actionType' => 'overlay',
            'route' => isset($parameters['route']) ? $parameters['route'] : 'enhavo_category_collection_update',
            'label' => isset($parameters['label']) ? $parameters['label'] : 'category.label.manageCategories',
            'icon' => isset($parameters['icon']) ? $parameters['icon'] : 'create',
            'routeParameters' => [
                'collection' => $collection
            ],
            'translationDomain' => isset($parameters['translationDomain']) ? $parameters['translationDomain'] : 'EnhavoCategoryBundle',
            'display' =>  isset($parameters['display']) ? $parameters['display'] : true,

        ]);
    }

    public function getType()
    {
        return 'category';
    }
}