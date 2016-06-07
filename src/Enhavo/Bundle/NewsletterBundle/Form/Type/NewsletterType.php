<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsletterType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    protected $securityContext;

    public function __construct($securityContext, $dataClass)
    {
        $this->securityContext = $securityContext;
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ) );

        $builder->add('subject', 'text', array(
            'label' => 'newsletter.form.label.subject',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ) );

        $builder->add('text', 'enhavo_wysiwyg', array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle'
        ) );

        if($this->securityContext->isGranted('WORKFLOW_ACTIVE', $this->dataClass)){
            $entityName = array();
            $entityName[0] = $this->dataClass;

            $builder->add('workflow_status', 'enhavo_workflow_status', array(
                'label' => 'workflow.form.label.next_state',
                'translation_domain' => 'EnhavoWorkflowBundle',
                'attr' => $entityName
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\NewsletterBundle\Entity\Newsletter'
        ));
    }

    public function getName()
    {
        return 'enhavo_newsletter_newsletter';
    }
}