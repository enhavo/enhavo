<?php


namespace Enhavo\Bundle\MediaBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'enhavo_download';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'required' => false,
            'translation_domain' => 'EnhavoMediaBundle',
            'label' => 'media.form.label.download'
        ]);
    }
}
