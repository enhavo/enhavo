<?php

namespace Enhavo\Bundle\WorkflowBundle\Form\Type;

use Enhavo\Bundle\WorkflowBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\WorkflowBundle\Entity\Workflow;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransitionType extends AbstractType
{
    public $workflow;

    public function __construct ($workflow)
    {
        $this->workflow = $workflow;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workflow = $this->workflow;
        $builder->add('transition_name', 'text', array(
            'label' => 'transition.form.label.transitionName',
            'translation_domain' => 'EnhavoWorkflowBundle'
        ) );

        $builder->add('node_from', 'entity', array(
            'label' => 'transition.form.label.nodeFrom',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'class' => 'EnhavoWorkflowBundle:Node',
            'choice_label'   => 'node_name',
            'query_builder' => function (EntityRepository $er) use ($workflow) {
                return $er->createQueryBuilder('n')
                    ->join('EnhavoWorkflowBundle:Transition', 't', 'WITH', 't.workflow = :workflow');
            },
        ));

        $builder->add('node_to', 'entity', array(
            'label' => 'transition.form.label.nodeTo',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'class' => 'EnhavoWorkflowBundle:Node',
            'choice_label'   => 'node_name',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('n')
                    ->join('EnhavoWorkflowBundle:Transition', 't', 'WITH', 'n.workflow = t.workflow');
            },
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\WorkflowBundle\Entity\Transition'
        ));
    }

    public function getName()
    {
        return 'enhavo_workflow_transition';
    }
}