<?php

namespace Enhavo\Bundle\CategoryBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class CategoryEntityType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var string
     */
    protected $categoryDefaultCollection;

    public function __construct(ObjectManager $manager, $dataClass, $categoryDefaultCollection)
    {
        $this->manager = $manager;
        $this->categoryDefaultCollection = $categoryDefaultCollection;
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $manager = $this->manager;
        $resolver->setNormalizer('query_builder', function (Options $options, $value) use ($manager) {
            return $manager->getRepository('EnhavoCategoryBundle:Category')->getByCollectionQuery($options['category_name']);
        });

        $resolver->setDefaults(array(
            'expanded' => true,
            'multiple' => true,
            'class' => $this->dataClass,
            'category_name' => $this->categoryDefaultCollection,
            'translation_domain' => 'EnhavoCategoryBundle'
        ));

        $resolver->setNormalizer('label', function(Options $options, $value) {
            if(empty($value)) {
                if ($options['multiple']) {
                    return 'category.label.categories';
                }
                return 'category.label.category';
            }
            return $value;
        });
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'enhavo_category';
    }
}