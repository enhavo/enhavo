<?php

namespace Enhavo\Bundle\DownloadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DownloadType extends AbstractType
{
    protected $dataClass;

    /**
     * @var boolean
     */
    protected $translation;

    public function __construct($dataClass, $translation)
    {
        $this->dataClass = $dataClass;
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('text', 'enhavo_wysiwyg', array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('file', 'enhavo_files', array(
            'label' => 'download.form.label.file',
            'translation_domain' => 'EnhavoDownloadBundle',
            'multiple'  => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_download_download';
    }
}