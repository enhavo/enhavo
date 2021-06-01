<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Type;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\AppBundle\Util\ArrayUtil;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SaveActionType extends AbstractActionType implements ActionTypeInterface
{
    /** @var RouterInterface */
    private $router;

    /**
     * CreateAction constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, ExpressionLanguage $expressionLanguage, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage, RequestStack $requestStack, RouterInterface $router)
    {
        parent::__construct($translator, $expressionLanguage, $authorizationChecker, $tokenStorage, $requestStack);
        $this->router = $router;
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = parent::createViewData($options, $resource);

        $url = null;
        if ($options['route']) {
            $url = $this->getUrl($options, $resource);
        }

        $data = ArrayUtil::merge($data, [
            'url' => $url
        ]);

        return $data;
    }

    private function getUrl(array $options, $resource = null)
    {
        $parameters['id'] = $resource->getId();
        $parameters = array_merge_recursive($parameters, $options['route_parameters']);
        return $this->router->generate($options['route'], $parameters);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'component' => 'save-action',
            'label' => 'label.save',
            'translation_domain' => 'EnhavoAppBundle',
            'icon' => 'save',
            'route' => null,
            'route_parameters' => [],
        ]);
    }

    public function getType()
    {
        return 'save';
    }
}
