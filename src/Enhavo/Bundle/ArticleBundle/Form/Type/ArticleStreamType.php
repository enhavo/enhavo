<?php

namespace Enhavo\Bundle\ArticleBundle\Form\Type;


use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleStreamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pagination', BooleanType::class, [
            'label' => 'pagination'
        ]);

        $builder->add('limit', IntegerType::class, [
            'label' => 'limit'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Enhavo\Bundle\ArticleBundle\Entity\ArticleStream'
        ));
    }

    public function getName()
    {
        return 'enhavo_article_article_stream';
    }
}
