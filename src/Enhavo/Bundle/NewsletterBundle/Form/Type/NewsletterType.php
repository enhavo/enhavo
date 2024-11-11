<?php

namespace Enhavo\Bundle\NewsletterBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Form\Type\BlockNodeType;
use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Enhavo\Bundle\FormBundle\Form\Type\DateTimeType;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\NewsletterBundle\Model\GroupInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $templates;

    public function __construct($dataClass, $templates, TranslatorInterface $translator)
    {
        $this->dataClass = $dataClass;
        $this->translator = $translator;
        $this->templates = $templates;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('slug', TextType::class, [
            'label' => 'newsletter.form.label.slug',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ]);

        $builder->add('subject', TextType::class, [
            'label' => 'newsletter.form.label.subject',
            'translation_domain' => 'EnhavoNewsletterBundle'
        ]);

        $builder->add('groups', AutoCompleteEntityType::class, [
            'label' => 'group.label.groups',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'multiple' => true,
            'class' => GroupInterface::class,
            'route' => "enhavo_newsletter_admin_api_group_auto_complete",
        ]);

        $builder->add('content', BlockNodeType::class, [
            'label' => 'form.label.content',
            'translation_domain' => 'EnhavoAppBundle',
            'item_groups' => ['newsletter', 'simple']
        ]);

        $builder->add('attachments', MediaType::class, [
            'label' => 'newsletter.form.label.attachments',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'multiple' => true,
            'required' => false
        ]);

        $builder->add('createdAt', DateTimeType::class, [
            'disabled' => true
        ]);

        $builder->add('startAt', DateTimeType::class, [
            'disabled' => true
        ]);

        $builder->add('finishAt', DateTimeType::class, [
            'disabled' => true
        ]);

        $builder->add('state', TextType::class, [
            'disabled' => true
        ]);

        $builder->add('id', TextType::class, [
            'disabled' => true
        ]);


        if(count($this->templates) > 1) {
            $choices = [];
            foreach($this->templates as $key => $values) {
                $choices[$this->translator->trans($values['label'], [], $values['translation_domain'])] = $key;
            }

            $builder->add('template', ChoiceType::class, array(
                'label' => 'newsletter.form.label.template',
                'translation_domain' => 'EnhavoNewsletterBundle',
                'choices' => $choices
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_newsletter_newsletter';
    }
}
