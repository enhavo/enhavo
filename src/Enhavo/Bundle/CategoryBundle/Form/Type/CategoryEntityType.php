<?php

namespace Enhavo\Bundle\CategoryBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $manager = $this->manager;

        $resolver->setRequired(array(
            'category_name'
        ));

        $resolver->setNormalizers(array(
            'query_builder' => function (Options $options, $configs) use ($manager) {
                    return function() use ($manager, $options) {
                        return $manager->getRepository('enhavoCategoryBundle:Category')->getByCollectionName($options['category_name']);
                    };
                },
        ));

        $resolver->setDefaults(array(
            'expanded' => true,
            'multiple' => true,
            'class' => 'Enhavo\Bundle\CategoryBundle\Entity\Category'
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