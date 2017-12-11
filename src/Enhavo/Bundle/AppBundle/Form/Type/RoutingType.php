<?php
/**
 * RoutingType.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Exception\UrlResolverException;
use Enhavo\Bundle\AppBundle\Route\GeneratorInterface;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Route\Routing;
use Enhavo\Bundle\AppBundle\Route\Slugable;
use Enhavo\Bundle\AppBundle\Route\UrlResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RoutingType extends AbstractType
{
    use ContainerAwareTrait;

    /**
     * @var UrlResolverInterface
     */
    protected $urlResolver;

    public function __construct(UrlResolverInterface $urlResolver)
    {
        $this->urlResolver = $urlResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($options['routing_strategy'] === Routing::STRATEGY_ID) {
                if (!method_exists($data, 'getId')) {
                    throw new \Exception('Routing strategy id used, but data has no getId method');
                }
            }

            if ($options['routing_strategy'] === Routing::STRATEGY_SLUG) {
                if (!$data instanceof Slugable || !method_exists($data, 'getSlug')) {
                    throw new \Exception('Routing strategy slug used, but data has no getSlug method nor is instanceof Slugable');
                }

                $form->add('slug', 'enhavo_slug');
            }

            if ($options['routing_strategy'] === Routing::STRATEGY_SLUG_ID) {
                if (!$data instanceof Slugable || !method_exists($data, 'getSlug')) {
                    throw new \Exception('Routing strategy id_slug used, but data has no getSlug method nor is instanceof Slugable');
                }

                if (!method_exists($data, 'getId')) {
                    throw new \Exception('Routing strategy id_slug used, but data has no getId method');
                }

                $form->add('slug', 'enhavo_slug', array());
            }

            if ($options['routing_strategy'] === Routing::STRATEGY_ROUTE) {
                if (!$data instanceof Routeable) {
                    throw new \Exception('Routing strategy route used, but data is not instanceof Routeable');
                }

                $form->add('route', 'enhavo_route');
            }
            
            if($data) {
                try {
                    $url = $this->urlResolver->resolve($data, UrlGeneratorInterface::ABSOLUTE_URL);
                    $form->add('link', 'text', array(
                        'mapped' => false,
                        'data' => $url,
                        'read_only' => true
                    ));
                } catch (UrlResolverException $e) {
                    return;
                }
            }
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            if ($options['routing_strategy'] === Routing::STRATEGY_ROUTE) {
                $data = $event->getData();
                if($data instanceof Routeable) {
                    $route = $data->getRoute();
                    if($route instanceof Route && empty($route->getStaticPrefix())) {
                        /** @var GeneratorInterface $generator */
                        $generator = $this->container->get($options['routing_generator']);
                        $url = $generator->generate($data);
                        if($url !== null) {
                            $route->setStaticPrefix($url);
                        }
                    }
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'routing_strategy' => null,
            'routing_route' => null,
            'routing_generator' => 'enhavo_app.route_guess_generator'
        ));
    }

    public function getName()
    {
        return 'enhavo_routing';
    }
}