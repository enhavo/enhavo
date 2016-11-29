<?php

namespace Enhavo\Bundle\DownloadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Enhavo\Bundle\DownloadBundle\Entity\Download;

class DownloadType extends AbstractType
{
    protected $dataClass;

    /**
     * @var boolean
     */
    protected $translation;

    public function __construct($dataClass, $translation, $router)
    {
        $this->dataClass = $dataClass;
        $this->translation = $translation;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data instanceof Download && !empty($data->getId())) {
                $form->add('link', 'text', array(
                    'mapped' => false,
                    'data' => $this->router->generate('enhavo_media_download', array(
                        'id' => $data->getId()
                    ), true),
                    'read_only' => true
                ));
            }
        });

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