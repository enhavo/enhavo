<?php
/**
 * RoutingType.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\RoutingBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Exception\UrlResolverException;
use Enhavo\Bundle\AppBundle\Routing\Routeable;
use Enhavo\Bundle\AppBundle\Routing\Routing;
use Enhavo\Bundle\AppBundle\Routing\Slugable;
use Enhavo\Bundle\AppBundle\Routing\UrlResolverInterface;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RouterType extends AbstractType
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router= $this->router;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options, $router) {
            $data = $event->getData();
            $form = $event->getForm();

            $url = $router->generate($data);

            $form->add($options['name'], TextType::class, [
                'mapped' => false,
                'data' => $url,
                'read_only' => true
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'default',
            'name' => 'routing_router'
        ));
    }

    public function getName()
    {
        return 'enhavo_router';
    }
}