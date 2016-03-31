<?php

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Enhavo\Bundle\UserBundle\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TableType extends AbstractType
{
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workflowId = $builder->getOptions()['attr'][0];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($workflowId) {
            $form = $event->getForm();
            $data = $event->getData();

            $workflow = $this->manager->getRepository('EnhavoWorkflowBundle:Workflow')->find($workflowId);
            $type = strtoupper($workflow->getEntity());
            $nodes = $this->manager->getRepository('EnhavoWorkflowBundle:Node')->findBy(array(
                'workflow' => $workflow
            ));

            $groups = $this->manager->getRepository('EnhavoUserBundle:Group')->findAll();
            $creationGroups = array();
            $editGroups = array();
            foreach($groups as $group) {
                if($group->hasRole('ROLE_ENHAVO_'.$type.'_'.$type.'_CREATE')){
                    $creationGroups[] = $group;
                }
                if($group->hasRole('ROLE_ENHAVO_'.$type.'_'.$type.'_UPDATE')){
                    $editGroups[] = $group;
                }
            }
            $cgNames = array();
            foreach($creationGroups as $creationGroup) {
                $cgNames[] = $creationGroup->getName();
            }
            $egNames = array();
            foreach($editGroups as $editGroup) {
                $egNames[] = $editGroup->getName();
            }
            $form->add('creationRoles', 'collection', array(
                'type'   => 'entity',
                'options'  => array(
                    'class' => 'EnhavoUserBundle:Group',
                    'expanded' => true,
                    'multiple' => true,
                    'choices'  => $creationGroups
                ),
            ));
            $form->add('editRoles', 'collection', array(
                'entry_type' => 'choice',
                'entry_options' => array(
                    'choices' => $egNames
                ),
            ));
            $test = $event->getData();
            $event->setData($form);
            /*$event->setData(array(
                'nodes' => $nodes
            ));*/
        });
    }
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function getName()
    {
        return 'enhavo_table';
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }
}