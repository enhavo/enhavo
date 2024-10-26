<?php

namespace Enhavo\Bundle\MediaBundle\Form\Extension;

use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Enhavo\Bundle\VueFormBundle\Form\AbstractVueTypeExtension;
use Enhavo\Bundle\VueFormBundle\Form\VueData;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MediaVueTypeExtension extends AbstractVueTypeExtension
{
    public function __construct(
        private readonly MediaManager $mediaManager,
        private readonly RouterInterface $router,
        private readonly TranslatorInterface $translator,
    )
    {
    }
    public function buildVueData(FormView $view, VueData $data, array $options): void
    {
        $data['multiple'] = $view->vars['multiple'];
        $data['actions'] = $view->vars['actions'];
        $data['upload'] = $view->vars['upload'];
        $data['uploadLabel'] = $this->translator->trans('media.form.action.upload', [], 'EnhavoMediaBundle');
        $data['maxUploadSize'] = $this->mediaManager->getMaxUploadSize();
        $data['editable'] = true;
        $data['uploadUrl'] = $this->router->generate($view->vars['route']);
    }

    public function configureOptions(OptionsResolver $resolver): void
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
