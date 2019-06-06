<?php
/**
 * GalleryType.php
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\FormBundle\Form\Type\WysiwygType;
use Enhavo\Bundle\GridBundle\Model\Item\GalleryItem;
use Enhavo\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryItemType extends AbstractType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('text', WysiwygType::class, array(
            'label' => 'form.label.text',
            'translation_domain' => 'EnhavoAppBundle',
            'translation' => $this->translation
        ));

        $builder->add('files', MediaType::class, array(
            'label' => 'gallery.form.label.pictures',
            'translation_domain' => 'EnhavoGridBundle'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => GalleryItem::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_item_gallery';
    }
} 