<?php

namespace Enhavo\Bundle\DownloadBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;

class DownloadItemType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('download', 'entity', array(
            'class' => 'Enhavo\Bundle\DownloadBundle\Entity\Download',
            'property' => 'title',
            'multiple' => false,
            'expanded' => false,
            'empty_value' => 'label.download.item.choose',
            'label' => 'form.label.download'
        ));

        $builder->add('title', 'text', array(
            'label' => 'form.label.title'
        ));

        $builder->add('file', 'enhavo_files', array(
            'label' => 'form.label.file'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\DownloadBundle\Entity\DownloadItem'
        ));
    }

    public function getName()
    {
        return 'enhavo_download_item_download';
    }
}