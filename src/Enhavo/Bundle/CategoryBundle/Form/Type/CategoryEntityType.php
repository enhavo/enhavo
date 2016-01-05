<?php

namespace Enhavo\Bundle\CategoryBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class CategoryEntityType extends AbstractType
{
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
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
            'class' => 'Enhavo\Bundle\CategoryBundle\Entity\Category',
            'category_name' => null
        ));
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