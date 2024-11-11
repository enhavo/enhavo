<?php

namespace Enhavo\Bundle\RevisionBundle\Revision;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\RevisionBundle\Entity\Archive;
use Enhavo\Bundle\RevisionBundle\Entity\Bin;
use Enhavo\Bundle\RevisionBundle\Model\RevisionInterface;
use Enhavo\Bundle\RevisionBundle\Restore\Restorer;

class RevisionManager
{
    public function __construct(
        private readonly ResourceManager $resourceManager,
        private readonly EntityManagerInterface $em,
        private readonly Restorer $restorer,
        private readonly FactoryInterface $binFactory,
        private readonly FactoryInterface $archiveFactory,
    )
    {
    }

    public function saveRevision(RevisionInterface $subject): RevisionInterface
    {
        /** @var RevisionInterface $revision */
        $revision = $this->resourceManager->duplicate($subject);
        $revision->setRevisionState(RevisionInterface::STATE_REVISION);
        $revision->setRevisionDate(new \DateTime());
        $revision->setRevisionSubject($subject);
        $this->em->persist($revision);
        $this->em->flush();
        return $revision;
    }

    /** @return RevisionInterface[] */
    public function getRevisions(RevisionInterface $subject): array
    {
        $this->em->getFilters()->disable('revision');

        $queryBuilder = $this->em->getRepository(get_class($subject))->createQueryBuilder('s');
        $queryBuilder->andWhere('s.revisionState = :state');
        $queryBuilder->andWhere('s.revisionSubject = :subject');
        $queryBuilder->setParameter('subject', $subject);
        $queryBuilder->setParameter('state', RevisionInterface::STATE_REVISION);
        $queryBuilder->orderBy('s.revisionDate', 'DESC');

        $revisions = $queryBuilder->getQuery()->getResult();

        $this->em->getFilters()->enable('revision');

        return $revisions;
    }

    public function getRevision(RevisionInterface $subject, int $revisionId): RevisionInterface
    {
        $this->em->getFilters()->disable('revision');

        $queryBuilder = $this->em->getRepository(get_class($subject))->createQueryBuilder('s');

        $queryBuilder->andWhere('s.revisionState = :state');
        $queryBuilder->andWhere('s.revisionSubject = :subject');
        $queryBuilder->andWhere('s.id = :revisionId');

        $queryBuilder->setParameter('subject', $subject);
        $queryBuilder->setParameter('state', RevisionInterface::STATE_REVISION);
        $queryBuilder->setParameter('revisionId', $revisionId);

        $revision = $queryBuilder->getQuery()->getOneOrNullResult();

        $this->em->getFilters()->enable('revision');

        return $revision;
    }


    public function softDelete(RevisionInterface $subject): void
    {
        $now = new \DateTime();
        $subject->setRevisionState(RevisionInterface::STATE_DELETED);
        $subject->setRevisionDate($now);

        /** @var Bin $bin */
        $bin = $this->binFactory->createNew();
        $bin->setSubject($subject);
        $bin->setDate($now);
        $bin->setTitle($subject->getRevisionTitle());
        $bin->setResourceAlias($this->resourceManager->getMetadata($subject)?->getAlias());
        $this->em->persist($bin);

        $this->em->flush();
    }

    public function undelete(RevisionInterface $subject): void
    {
        $subject->setRevisionState(RevisionInterface::STATE_MAIN);
        $subject->setRevisionDate(null);
        $this->em->flush();
    }

    public function publish(RevisionInterface $subject): void
    {

    }

    public function getPublish(RevisionInterface $subject): RevisionInterface
    {
        return $subject;
    }

    public function archive(RevisionInterface $subject): void
    {
        $now = new \DateTime();
        $subject->setRevisionState(RevisionInterface::STATE_ARCHIVE);
        $subject->setRevisionDate($now);

        /** @var Archive $archive */
        $archive = $this->archiveFactory->createNew();
        $archive->setSubject($subject);
        $archive->setDate($now);
        $archive->setTitle($subject->getRevisionTitle());
        $archive->setResourceAlias($this->resourceManager->getMetadata($subject)?->getAlias());
        $this->em->persist($archive);

        $this->em->flush();
    }

    public function dearchive(RevisionInterface $subject): void
    {
        $subject->setRevisionState(null);
        $subject->setRevisionDate(null);
        $this->em->flush();
    }

    public function restore(RevisionInterface $subject, RevisionInterface $revision): void
    {
        $revisions = $this->getRevisions($subject);
        if (!in_array($revision, $revisions)) {
            throw new \InvalidArgumentException('Revision must be a revision of subject');
        }

        $this->restorer->restore($subject, $revision);
        $this->em->flush();
    }
}
