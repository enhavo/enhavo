<?php

namespace Enhavo\Bundle\WorkflowBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\WorkflowBundle\Entity\Workflow;

class WorkflowType extends AbstractType
{
    public $workflow;

    public function __construct ($workflow)
    {
        $this->workflow = $workflow;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('workflow_name', 'text', array(
            'label' => 'workflow.form.label.workflowName',
            'translation_domain' => 'EnhavoWorkflowBundle'
        ) );

        $builder->add('nodes', 'enhavo_list', array(
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