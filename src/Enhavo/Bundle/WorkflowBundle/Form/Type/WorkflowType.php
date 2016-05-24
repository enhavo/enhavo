<?php

namespace Enhavo\Bundle\WorkflowBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\WorkflowBundle\Entity\Workflow;
use Enhavo\Bundle\AppBundle\Form\Type\MessageType;

class WorkflowType extends AbstractType
{
    protected $container;
    protected $em;

    public function __construct($container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'label' => 'workflow.form.label.name',
            'translation_domain' => 'EnhavoWorkflowBundle'
        ) );

        $entities = $this->getWorkflowEntities($this->container->getParameter('enhavo_workflow.entities'));

        $builder->add('entity', 'choice', array(
            'label' => 'workflow.form.label.type',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'choices'   => $entities,
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('active', 'enhavo_boolean');

        $builder->add('formNodes', 'enhavo_list', array(
            'type' => 'enhavo_workflow_node',
            'label' => 'workflow.form.label.nodes',
            'prototype' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'translation_domain' => 'EnhavoWorkflowBundle'
        ));

        $builder->add('transitions', 'enhavo_list', array(
            'type' => 'enhavo_workflow_transition',
            'label' => 'workflow.form.label.transitions',
            'prototype' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'translation_domain' => 'EnhavoWorkflowBundle'
        ));

        $builder->add('warning', 'enhavo_message', array(
            'type' => MessageType::MESSAGE_TYPE_WARNING,
            'translation_domain' => 'EnhavoWorkflowBundle',
            'message' => 'workflow.form.message.deleteNode'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\WorkflowBundle\Entity\Workflow'
        ));
    }

    public function getName()
    {
        return 'enhavo_workflow_workflow';
    }

    public function getWorkflowEntities($entityArray)
    {
        $translator = $this->container->get('translator');
        $finalEntities = array();
        $workflowRepository = $this->em->getRepository('EnhavoWorkflowBundle:Workflow');
        $workflows = $workflowRepository->findAll();
        foreach($entityArray as $entityData) {
            $entityName = $entityData['class'];
            $entityHasWF = false;
            foreach($workflows as $workflow){
                if($workflow->getEntity() == $entityName){
                    $entityHasWF = true;
                }
            }
            if($entityHasWF == false){
                $finalEntities[$entityName] = $translator->trans($entityData['label'], array(), $entityData['translationDomain']);
            }
        }
        return $finalEntities;
    }
}