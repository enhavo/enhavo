<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-19
 * Time: 02:15
 */

namespace Enhavo\Bundle\AppBundle\Action;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractActionType implements ActionTypeInterface
{
    /** @var TranslatorInterface */
    protected $translator;

    /** @var ExpressionLanguage */
    private $expressionLanguage;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var RequestStack */
    private $requestStack;

    /**
     * AbstractActionType constructor.
     * @param TranslatorInterface $translator
     * @param ExpressionLanguage $expressionLanguage
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     */
    public function __construct(TranslatorInterface $translator, ExpressionLanguage $expressionLanguage, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->expressionLanguage = $expressionLanguage;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    public function isHidden(array $options, $resource = null)
    {
        if (preg_match('/^exp:/', $options['hidden'])) {
            $request = $this->requestStack->getCurrentRequest();
            $user = $this->tokenStorage->getToken()->getUser();
            $hidden = $this->expressionLanguage->evaluate(substr($options['hidden'], 4), [
                'resource' => $resource,
                'request' => $request,
                'user' => $user,
                'authorizationChecker' => $this->authorizationChecker,
                'action' => $this
            ]);
        } else {
            $hidden = $options['hidden'];
        }
        return $hidden;
    }

    public function getPermission(array $options, $resource = null)
    {
        return $options['permission'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => null,
            'translation_domain' => null,
            'label' => null,
            'permission' => null,
            'hidden' => false
        ]);

        $resolver->setRequired([
            'component'
        ]);
    }

    public function createViewData(array $options, $resource = null)
    {
        $data = [
            'component' => $options['component'],
            'icon' => $options['icon'],
            'label' => $this->getLabel($options),
        ];

        return $data;
    }

    protected function getLabel(array $options)
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }
}
