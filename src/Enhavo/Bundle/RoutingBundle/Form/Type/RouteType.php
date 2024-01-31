<?php
/**
 * RouteType.php
 *
 * @since 19/05/15
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\RoutingBundle\Form\Type;

use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class RouteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => 'label.url',
            'translation_domain' => 'EnhavoAppBundle',
            'data_class' => Route::class,
            'empty_data' => function (FormInterface $form): ?Route {
                $path = $form->get('path')->getData();
                if (empty($path)) {
                    return null;
                }
                $route = new Route();
                $route->setPath($path);
                $route->generateRouteName();
                return $route;
            },
        ));
    }

    public function getBlockPrefix()
    {
        return 'enhavo_route';
    }
}
