<?php
/**
 * @author blutze-media
 * @since 2022-02-28
 */

namespace Enhavo\Bundle\MediaLibraryBundle\Form\Type;

use Enhavo\Bundle\MediaLibraryBundle\Media\MediaLibraryManager;
use Enhavo\Bundle\TaxonomyBundle\Form\Type\TermAutoCompleteChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function __construct(
        private readonly MediaLibraryManager $mediaLibraryManager,
        private readonly string $dataClass,
    )
    {
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
                'route' => 'enhavo_media_library_tag_auto_complete',
                'translation_domain' => 'EnhavoMediaLibraryBundle',
                'create_route' => 'enhavo_media_library_tag_create',
                'edit_route' => 'enhavo_media_library_tag_update',
                'frame_key' => 'media_library_tags'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
            'config' => 'default',
        ]);
    }
}
