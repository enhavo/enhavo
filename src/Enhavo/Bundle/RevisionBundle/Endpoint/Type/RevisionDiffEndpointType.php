<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\RevisionBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Enhavo\Bundle\ResourceBundle\Authorization\Permission;
use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Change;
use Enhavo\Bundle\ResourceBundle\Duplicate\DiffFactory;
use Enhavo\Bundle\ResourceBundle\Input\Input;
use Enhavo\Bundle\ResourceBundle\Input\InputFactory;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Revision\RevisionManager;
use Enhavo\Bundle\RevisionBundle\Tab\Type\RevisionTabType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Returns the changes of a revision, compared to the revision saved before it (query compare=previous, default)
 * or to the current state of the resource (compare=current).
 */
class RevisionDiffEndpointType extends AbstractEndpointType
{
    public function __construct(
        private readonly InputFactory $inputFactory,
        private readonly RevisionManager $revisionManager,
        private readonly DiffFactory $diffFactory,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Input $input */
        $input = $this->inputFactory->create($options['input']);

        $resource = $input->getResource();
        if (!$resource instanceof RevisionInterface) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted($input->getPermission($options['permission']), $resource);

        // newest first, so the previous revision is the next entry
        $revisions = array_values($this->revisionManager->getRevisions($resource));
        $revisionId = (int) $request->attributes->get('revisionId');
        $index = null;
        foreach ($revisions as $key => $revision) {
            if (method_exists($revision, 'getId') && $revision->getId() === $revisionId) {
                $index = $key;
                break;
            }
        }
        if (null === $index) {
            throw $this->createNotFoundException();
        }

        if (RevisionTabType::COMPARE_CURRENT === $request->query->get('compare')) {
            $older = $revisions[$index];
            $newer = $resource;
            $olderDate = $older->getRevisionDate();
            $newerDate = $resource instanceof Timestampable ? $resource->getUpdatedAt() : null;
        } else {
            $older = $revisions[$index + 1] ?? null;
            $newer = $revisions[$index];
            if (null === $older) {
                // the oldest revision has nothing to compare with
                $data->set('first', true);
                $data->set('changes', []);

                return;
            }
            $olderDate = $older->getRevisionDate();
            $newerDate = $newer->getRevisionDate();
        }

        // only what a revision stores is compared
        $changes = $this->diffFactory->diff($older, $newer, ['groups' => 'revision'])->getChanges();

        $data->set('first', false);
        $data->set('olderDate', $olderDate?->format(\DateTimeInterface::ATOM));
        $data->set('newerDate', $newerDate?->format(\DateTimeInterface::ATOM));
        $data->set('changes', array_map(fn (Change $change) => $this->normalizeChange($change, $options['translation_domain']), $changes));
    }

    private function normalizeChange(Change $change, string $translationDomain): array
    {
        $section = null;
        if (null !== $change->getSection()) {
            $section = [
                'label' => $this->translator->trans('revision.diff.block', [
                    '%position%' => $change->getSection()->getPosition(),
                    '%type%' => $change->getSection()->getLabel(),
                ], $translationDomain),
                'status' => $change->getSection()->getStatus(),
            ];
        }

        return [
            'section' => $section,
            'field' => $change->getField(),
            'old' => $change->getOld(),
            'new' => $change->getNew(),
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'EnhavoRevisionBundle',
            'permission' => Permission::UPDATE,
        ]);
        $resolver->setRequired('input');
    }

    public static function getName(): ?string
    {
        return 'revision_diff';
    }
}
