<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreviewActionType extends AbstractActionType implements ActionTypeInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        parent::__construct($translator);
        $this->router = $router;
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);
        $data['url'] = $this->router->generate($options['route'], [
            'id' => $resource ? $resource->getId() : null,
        ]);
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'preview-action',
            'label' => 'label.preview',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'remove_red_eye',
        ]);

        $resolver->setRequired(['route']);
    }

    public function getType()
    {
        return 'preview';
    }
}
