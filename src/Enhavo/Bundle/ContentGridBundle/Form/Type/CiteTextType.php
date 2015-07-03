<?php
/**
 * CiteTextType.php
 *
 */

namespace Enhavo\Bundle\ContentGridBundle\Form\Type;

use Enhavo\Bundle\ContentGridBundle\Item\ItemFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Enhavo\Bundle\ContentGridBundle\Item\Type\Text;

class CiteTextType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cite', 'textarea', array(
            'label' => 'form.label.cite'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\ContentGridBundle\Entity\CiteText'
        ));
    }

    public function getName()
    {
        return 'enhavo_content_grid_item_citetext';
    }
} 