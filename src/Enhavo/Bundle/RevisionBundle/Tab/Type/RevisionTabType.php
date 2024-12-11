<?php

namespace Enhavo\Bundle\RevisionBundle\Tab\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionLanguage;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RevisionTabType extends AbstractTabType
{
    public function __construct(
        private readonly RevisionManager $revisionManager,
        private readonly NormalizerInterface $normalizer,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createViewData(array $options, Data $data, InputInterface $input = null): void
    {
        $resource = $input->getResource();
        $route = $this->getRoute($options);

        $revisions = [];
        if ($resource instanceof RevisionInterface) {

            $revisionData = $this->revisionManager->getRevisions($resource);

            foreach ($revisionData as $revision) {

                $routeParameters = $this->expressionLanguage->evaluateArray($options['route_parameters'], [
                    'resource' => $resource,
                    'revision' => $revision,
                ]);

                $revisions[] = [
                    'id' => $revision->getId(),
                    'date' => $this->normalizer->normalize($revision->getRevisionDate(), null, ['groups' => $options['serialization_groups']]),
                    'user' => $this->normalizer->normalize($revision->getRevisionUser(), null, ['groups' => $options['serialization_groups']]),
                    'url' => $this->router->generate($route, $routeParameters),
                ];
            }
        }

        $data->set('revisions', $revisions);
        $data->set('token', $this->tokenManager->getToken('resource_revision')->getValue());
        $data->set('confirmMessage', $this->translator->trans($options['confirm_message'], [], $options['translation_domain']));
        $data->set('confirmLabelOk', $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']));
        $data->set('confirmLabelCancel', $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']));
        $data->set('userLabel', $options['user_label']);
    }

    private function getRoute(array $options): string
    {
        $route = $this->expressionLanguage->evaluate($options['route']);

        if ($route === null) {
            $route = $this->routeResolver->getRoute('revision_restore', ['api' => true]);
        }

        if ($route === null) {
            throw new \Exception(
                'Can\'t find a route for revision restore, please provide a route to the revision tab over the "route" option'
            );
        }

        return $route;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'tab-revision',
            'model' => 'RevisionTab',
            'route' => null,
            'route_parameters' => [
                'id' => 'expr:resource.getId()',
                'revisionId' => 'expr:revision.getId()',
            ],
            'confirm_message' => 'restore.message.confirm',
            'confirm_label_ok' => 'restore.action.restore',
            'confirm_label_cancel' => 'restore.action.cancel',
            'translation_domain' => 'EnhavoRevisionBundle',
            'serialization_groups' => ['endpoint', 'endpoint.admin'],
            'user_label' => 'email',
        ]);
    }

    public static function getName(): ?string
    {
        return 'revision';
    }
}
