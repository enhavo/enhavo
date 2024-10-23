<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 03.02.18
 * Time: 00:10
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationType extends AbstractType
{
    public function __construct(
        private readonly string $dataClass,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'navigation.label.name',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('code', TextType::class, [
            'label' => 'navigation.label.code',
            'translation_domain' => 'EnhavoNavigationBundle',
        ]);

        $builder->add('nodes', NodeCollectionType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'items' => [],
            'item_groups' => [],
            'data_class' => $this->dataClass
        ]);
    }
}
