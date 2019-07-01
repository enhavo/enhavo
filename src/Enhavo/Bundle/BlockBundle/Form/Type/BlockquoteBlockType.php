<?php
/**
 * BlockquoteTextType.php
 *
 */

namespace Enhavo\Bundle\BlockBundle\Form\Type;

use Enhavo\Bundle\BlockBundle\Model\Block\BlockquoteBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockquoteBlockType extends AbstractType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, array(
            'label' => 'blockquoteText.form.label.blockquote',
            'translation_domain' => 'EnhavoBlockBundle',
            'translation' => $this->translation
        ));

        $builder->add('author', TextType::class, array(
            'label' => 'blockquoteText.form.label.author',
            'translation_domain' => 'EnhavoBlockBundle',
            'translation' => $this->translation
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BlockquoteBlock::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_block_block_blockquote';
    }
} 
