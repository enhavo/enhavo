<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RoutingBundle\Form\Type;

use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $router = $this->router;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options, $router): void {
            $data = $event->getData();
            $form = $event->getForm();

            $url = $router->generate($data);

            $form->add($options['name'], TextType::class, [
                'mapped' => false,
                'data' => $url,
                // 'read_only' => true
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => 'default',
            'name' => 'routing_router',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_router';
    }
}
