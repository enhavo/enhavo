<?php

namespace Enhavo\Bundle\WorkflowBundle\Form\Type;

use Enhavo\Bundle\WorkflowBundle\Entity\Node;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('transition_name', 'text', array(
            'label' => 'transition.form.label.transitionName',
            'translation_domain' => 'EnhavoWorkflowBundle'
        ) );

       /* $builder->add('node_from', 'choice', array(
            'label' => 'transition.form.label.nodeFrom',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'choices'   => $this->nodes,
            'expanded' => false,
            'multiple' => false
        ));*/
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