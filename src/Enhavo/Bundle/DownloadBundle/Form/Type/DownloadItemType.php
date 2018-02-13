<?php

namespace Enhavo\Bundle\DownloadBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Config\Condition;
use Enhavo\Bundle\DownloadBundle\Entity\DownloadItem;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\GridBundle\Item\ItemFormType;

class DownloadItemType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $condition = new Condition();

        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('downloadType', ChoiceType::class, array(
            'choices' => [
                DownloadItem::TYPE_DOWNLOAD => DownloadItem::TYPE_DOWNLOAD,
                DownloadItem::TYPE_FILE=> DownloadItem::TYPE_FILE
            ],
            'multiple' => false,
            'expanded' => true,
            'condition' => $condition,
            'label' => 'downloadItem.form.label.downloadType',
            'translation_domain' => 'EnhavoDownloadBundle',
        ));

        $builder->add('download', 'entity', array(
            'class' => 'Enhavo\Bundle\DownloadBundle\Entity\Download',
            'property' => 'title',
            'multiple' => false,
            'expanded' => false,
            'empty_value' => 'downloadItem.label.download.item.choose',
            'label' => 'downloadItem.form.label.download',
            'translation_domain' => 'EnhavoDownloadBundle',
            'condition_observer' => $condition->createObserver(DownloadItem::TYPE_DOWNLOAD)
        ));

        $builder->add('file', 'enhavo_files', array(
            'label' => 'downloadItem.form.label.file',
            'translation_domain' => 'EnhavoDownloadBundle',
            'multiple'  => false,
            'condition_observer' => $condition->createObserver(DownloadItem::TYPE_FILE)
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