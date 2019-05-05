<?php
namespace Enhavo\Bundle\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var string
     */
    protected $categoryType;

    public function __construct($dataClass, $categoryType = 'enhavo_collection_category')
    {
        $this->dataClass = $dataClass;
        $this->categoryType = $categoryType;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categories', 'enhavo_list', array(
            'type' => $this->categoryType
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_category_collection';
    }
}