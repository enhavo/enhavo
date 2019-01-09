<?php
/**
 * CiteTextType.php
 *
 */

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Model\Item\CiteItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CiteItemType extends AbstractType
{
    private $translation;

    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, array(
            'label' => 'citeText.form.label.cite',
            'translation_domain' => 'EnhavoGridBundle',
            'translation' => $this->translation
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CiteItem::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_grid_cite_item';
    }
} 