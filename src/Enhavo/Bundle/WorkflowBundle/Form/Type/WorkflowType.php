<?php

namespace Enhavo\Bundle\WorkflowBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\WorkflowBundle\Entity\Workflow;

class WorkflowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('workflow_name', 'text', array(
            'label' => 'workflow.form.label.workflowName',
            'translation_domain' => 'EnhavoWorkflowBundle'
        ) );

        $builder->add('entity', 'choice', array(
            'label' => 'workflow.form.label.type',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'choices'   => array(
                'article' => 'workflow.label.article',
                'page' => 'workflow.label.page',
            ),
            'expanded' => false,
            'multiple' => false
        ));

        $builder->add('nodes', 'enhavo_list', array(
            'type' => 'enhavo_workflow_node',
            'label' => 'workflow.form.label.nodes',
            'prototype' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'translation_domain' => 'EnhavoWorkflowBundle'
        ));

        /*$wfId = array();
        $wfId[0] = $builder->getData()->getId();

        $builder->add('transitions', 'enhavo_table', array(
            'label' => 'workflow.form.label.transitions',
            'attr' => $wfId
        ));*/
        /*$builder->add('transitions', 'collection', array(
            'entry_type'   => 'entity',
            'entry_options'  => array(
                'class' => 'EnhavoUserBundle:Group',
                'expanded' => true,
                'multiple' => true,
                'choices'  => array(
                    'Test1' => 'TEST1',
                    'Test2' => 'TEST2'
                )
            ),
        ));*/

        $builder->add('transitions', 'enhavo_list', array(
            'type' => 'enhavo_workflow_transition',
            'label' => 'workflow.form.label.transitions',
            'prototype' => true,
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'translation_domain' => 'EnhavoWorkflowBundle'
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
}