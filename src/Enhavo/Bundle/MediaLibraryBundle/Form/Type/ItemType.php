<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Form\Type;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Entity\Item;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoCompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function __construct(
        private readonly MediaLibraryManager $mediaLibraryManager,
        private readonly UrlGeneratorInterface $themeUrlGenerator,
        private readonly string $dataClass,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'config' => $options['config'],
            ])
            ->add('contentType', ChoiceType::class, [
                'label' => 'media_library.form.label.content_type',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
                'choices' => array_flip($this->mediaLibraryManager->getContentTypes()),
                'placeholder' => '---',
            ])
            ->add('tags', TermAutoCompleteChoiceType::class, [
                'label' => 'media_library.form.label.tags',
                'multiple' => true,
                'route' => 'enhavo_media_library_admin_api_tag_auto_complete',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
                'create_route' => 'enhavo_media_library_admin_tag_create',
                'edit_route' => 'enhavo_media_library_admin_tag_update',
                'frame_key' => 'media_library_tags',
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $form = $event->getForm();

            /** @var ?Item $data */
            $data = $event->getData();
            $urlValue = '';

            if ($data && $data->getFile() && $data->getFile() instanceof FileInterface) {
                $urlValue = $this->themeUrlGenerator->generate($data->getFile());
            }

            $form->add('url', TextType::class, [
                'label' => 'media_library.form.label.url',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
                'mapped' => false,
                'data' => $urlValue,
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'config' => 'default',
        ]);
    }
}
