<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public const COMPARE_PREVIOUS = 'previous';
    public const COMPARE_CURRENT = 'current';

    public function __construct(
        private readonly RevisionManager $revisionManager,
        private readonly NormalizerInterface $normalizer,
        private readonly CsrfTokenManagerInterface $tokenManager,
        private readonly RouterInterface $router,
        private readonly RouteResolverInterface $routeResolver,
        private readonly ResourceExpressionLanguage $expressionLanguage,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function createViewData(array $options, Data $data, ?InputInterface $input = null): void
    {
        $resource = $input->getResource();
        $restoreRoute = $options['restore'] ? $this->getRoute($options['route'], 'revision_restore', 'route') : null;
        $diffRoute = $options['diff'] ? $this->getRoute($options['diff_route'], 'revision_diff', 'diff_route') : null;

        $revisions = [];
        if ($resource instanceof RevisionInterface) {
            $revisionData = $this->revisionManager->getRevisions($resource);

            foreach ($revisionData as $revision) {
                $item = [
                    'id' => $revision->getId(),
                    'date' => $this->normalizer->normalize($revision->getRevisionDate(), null, ['groups' => $options['serialization_groups']]),
                    'user' => $this->normalizer->normalize($revision->getRevisionUser(), null, ['groups' => $options['serialization_groups']]),
                    'url' => null,
                    'diffUrl' => null,
                ];

                if ($restoreRoute) {
                    $routeParameters = $this->expressionLanguage->evaluateArray($options['route_parameters'], [
                        'resource' => $resource,
                        'revision' => $revision,
                    ]);
                    $item['url'] = $this->router->generate($restoreRoute, $routeParameters);
                }

                if ($diffRoute) {
                    $routeParameters = $this->expressionLanguage->evaluateArray($options['diff_route_parameters'], [
                        'resource' => $resource,
                        'revision' => $revision,
                    ]);
                    $item['diffUrl'] = $this->router->generate($diffRoute, array_merge($routeParameters, ['compare' => $options['compare']]));
                }

                $revisions[] = $item;
            }
        }

        $data->set('revisions', $revisions);
        $data->set('restore', $options['restore']);
        $data->set('diff', $options['diff']);
        $data->set('compare', $options['compare']);
        $data->set('token', $this->tokenManager->getToken('resource_revision')->getValue());
        $data->set('confirmMessage', $this->translator->trans($options['confirm_message'], [], $options['translation_domain']));
        $data->set('confirmLabelOk', $this->translator->trans($options['confirm_label_ok'], [], $options['translation_domain']));
        $data->set('confirmLabelCancel', $this->translator->trans($options['confirm_label_cancel'], [], $options['translation_domain']));
        $data->set('userLabel', $options['user_label']);
        if ($options['diff']) {
            $data->set('diffTexts', $this->translateDiffTexts($options['compare'], $options['translation_domain']));
        }
    }

    private function getRoute(?string $route, string $name, string $option): string
    {
        $route = $this->expressionLanguage->evaluate($route);

        if (null === $route) {
            $route = $this->routeResolver->getRoute($name, ['api' => true]);
        }

        if (null === $route) {
            throw new \Exception(sprintf('Can\'t find a route for %s, please provide a route to the revision tab over the "%s" option', $name, $option));
        }

        return $route;
    }

    /**
     * @return array<string, string>
     */
    private function translateDiffTexts(string $compare, string $translationDomain): array
    {
        $current = self::COMPARE_CURRENT === $compare;
        $keys = [
            'hint' => 'revision.diff.hint.'.$compare,
            // "newer" is shown in the left column, "older" in the right one
            'older' => $current ? 'revision.diff.column.revision' : 'revision.diff.column.before',
            'newer' => $current ? 'revision.diff.column.current' : 'revision.diff.column.revision',
            'first' => 'revision.diff.first',
            'noChanges' => 'revision.diff.no_changes.'.$compare,
            'loading' => 'revision.diff.loading',
            'error' => 'revision.diff.error',
            'added' => 'revision.diff.status.added',
            'removed' => 'revision.diff.status.removed',
        ];

        return array_map(fn (string $key) => $this->translator->trans($key, [], $translationDomain), $keys);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'tab-revision',
            'model' => 'RevisionTab',
            'restore' => true,
            'route' => null,
            'route_parameters' => [
                'id' => 'expr:resource.getId()',
                'revisionId' => 'expr:revision.getId()',
            ],
            'diff' => false,
            'diff_route' => null,
            'diff_route_parameters' => [
                'id' => 'expr:resource.getId()',
                'revisionId' => 'expr:revision.getId()',
            ],
            'compare' => self::COMPARE_PREVIOUS,
            'confirm_message' => 'restore.message.confirm',
            'confirm_label_ok' => 'restore.action.restore',
            'confirm_label_cancel' => 'restore.action.cancel',
            'translation_domain' => 'EnhavoRevisionBundle',
            'serialization_groups' => ['endpoint', 'endpoint.admin'],
            'user_label' => 'email',
            'label' => 'revision.label.revision',
        ]);
        $resolver->setAllowedValues('compare', [self::COMPARE_PREVIOUS, self::COMPARE_CURRENT]);
    }

    public static function getName(): ?string
    {
        return 'revision';
    }
}
