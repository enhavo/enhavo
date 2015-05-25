<?php

namespace esperanto\DownloadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use esperanto\ContentBundle\Item\ItemFormType;

class DownloadItemType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('download', 'entity', array(
            'class' => 'esperanto\DownloadBundle\Entity\Download',
            'property' => 'title',
            'multiple' => false,
            'expanded' => false,
            'empty_value' => 'label.download.item.choose',
            'label' => 'form.label.download'
        ));

        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('file', 'esperanto_files', array(
            'label' => 'form.label.file'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\DownloadBundle\Entity\DownloadItem'
        ));
    }

    public function getName()
    {
        return 'esperanto_download_item_download';
    }
}