<?php
/**
 * RoutingType.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Exception\UrlResolverException;
use Enhavo\Bundle\AppBundle\Routing\Routeable;
use Enhavo\Bundle\AppBundle\Routing\Routing;
use Enhavo\Bundle\AppBundle\Routing\Slugable;
use Enhavo\Bundle\AppBundle\Routing\UrlResolverInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SlugType extends AbstractType
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
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'routing_strategy' => null,
            'routing_route' => null
        ));
    }

    public function getName()
    {
        return 'enhavo_routing';
    }
}