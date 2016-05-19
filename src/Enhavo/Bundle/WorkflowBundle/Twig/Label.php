<?php

namespace Enhavo\Bundle\WorkflowBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class Label extends \Twig_Extension {
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var EngineInterface
     */
    protected $templateEngine;

    /**
     * @param Container $container
     * @param $template string
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('label', array($this, 'getLabel')),
        );
    }

    public function getLabel($class)
    {
        $translator = $this->container->get('translator');
        //find the current entity repository
        $entities = $this->container->getParameter('enhavo_workflow.entities');
        $label = null;
        foreach($entities as $entity){
            if($entity['class'] == $class) {
                $label = $translator->trans($entity['label'], array(), $entity['translationDomain']);
                break;
            }
        }

        return $label;
    }

    public function getName()
    {
        return 'get_label';
    }
}