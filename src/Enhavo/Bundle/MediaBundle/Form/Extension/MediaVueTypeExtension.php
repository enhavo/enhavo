<?php

namespace Enhavo\Bundle\MediaBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private MediaManager $mediaManager,
    )
    {
    }

    public function buildVueData(FormView $view, VueData $data, array $options)
    {
        $data['upload'] = $view->vars['upload'];
        $data['multiple'] = $view->vars['multiple'];
        $data['uploadLabel'] = 'Upload';
        $data['buttons'] = null;
        $data['maxUploadSize'] = $this->mediaManager->getMaxUploadSize();
        $data['editable'] = true;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'form-media',
            'component_model' => 'MediaForm',
            'item_component' => 'form-media-item',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [MediaType::class];
    }
}
