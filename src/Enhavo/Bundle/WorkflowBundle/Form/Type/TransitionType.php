<?php

namespace Enhavo\Bundle\WorkflowBundle\Form\Type;

use Enhavo\Bundle\WorkflowBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $split = explode('/', $uri);
        $workflow_id = array_pop($split);
        $builder->add('transition_name', 'text', array(
            'label' => 'transition.form.label.transitionName',
            'translation_domain' => 'EnhavoWorkflowBundle'
        ) );

        $builder->add('node_from', 'entity', array(
            'label' => 'transition.form.label.nodeFrom',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'class' => 'EnhavoWorkflowBundle:Node',
            'choice_label'   => 'node_name',
            'query_builder' => function (EntityRepository $er) use ($workflow_id) {
                return $er->createQueryBuilder('n')
                    ->setParameter('id', $workflow_id)
                    ->where('n.workflow = :id');
            },
        ));

        $builder->add('node_to', 'entity', array(
            'label' => 'transition.form.label.nodeTo',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'class' => 'EnhavoWorkflowBundle:Node',
            'choice_label'   => 'node_name',
            'query_builder' => function (EntityRepository $er) use ($workflow_id) {
                return $er->createQueryBuilder('n')
                    ->setParameter('id', $workflow_id)
                    ->where('n.workflow = :id');
            },
        ));
        $builder->add('groups', 'entity', array(
            'class' => 'EnhavoUserBundle:Group',
            'choice_label' => 'name',
            'multiple' => true
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