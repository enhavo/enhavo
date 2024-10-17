<?php
/**
 * @author blutze-media
 * @since 2022-02-28
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Form\Type;

use Enhavo\Bundle\MediaBundle\Form\Type\FileParametersType;
use Enhavo\Bundle\MediaBundle\Media\UrlGeneratorInterface;
use Enhavo\Bundle\MediaLibraryBundle\Entity\File;
use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoCompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function __construct(
        private MediaLibraryManager $mediaLibraryManager,
        private UrlGeneratorInterface $urlGenerator,
        private array $formConfiguration,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filename', TextType::class, [
                'label' => 'media_library.form.label.filename',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
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
                'route' => 'enhavo_media_library_tag_auto_complete',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
                'create_route' => 'enhavo_media_library_tag_create',
                'edit_route' => 'enhavo_media_library_tag_update',
                'frame_key' => 'media_library_tags'
            ])
            ->add('parameters', $this->formConfiguration['parameters_type'])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA,  function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $form->add('url', TextType::class, [
                'label' => 'media_library.form.label.url',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
                'mapped' => false,
                'data' => $this->urlGenerator->generate($data)
            ]);
        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => File::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'media_library_file';
    }
}
