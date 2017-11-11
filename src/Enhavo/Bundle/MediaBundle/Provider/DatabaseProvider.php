<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 20:52
 */

namespace Enhavo\Bundle\MediaBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Repository\FileRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class DatabaseProvider implements ProviderInterface
{
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @var RepositoryInterface
     */
    private $formatRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DatabaseProvider constructor.
     *
     * @param FileRepository $fileRepository
     * @param RepositoryInterface $formatRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(FileRepository $fileRepository, RepositoryInterface $formatRepository, EntityManagerInterface $em)
    {
        $this->fileRepository = $fileRepository;
        $this->formatRepository = $formatRepository;
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function findOneBy(array $criteria)
    {
        return $this->fileRepository->findOneBy($criteria);
    }

    /**
     * @inheritdoc
     */
    public function findBy(array $criteria = [], array $orderBy = [], $limit = null, $offset = null)
    {
        return $this->fileRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritdoc
     */
    public function find($id)
    {
        return $this->find($id);
    }

    /**
     * @inheritdoc
     */
    public function save(FileInterface $file)
    {
        $this->em->persist($file);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function delete(FileInterface $file)
    {
        $this->em->remove($file);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function findFormat(FileInterface $file, $format)
    {
        return $this->formatRepository->findOneBy([
            'name' => $format,
            'file' => $file
        ]);
    }

    /**
     * @inheritdoc
     */
    public function findAllFormats(FileInterface $file)
    {
        return $this->formatRepository->findBy([
            'file' => $file
        ]);
    }

    /**
     * @inheritdoc
     */
    public function saveFormat(FormatInterface $format)
    {
        $this->em->persist($format);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function deleteFormat(FormatInterface $format)
    {
        $this->em->remove($format);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function collectGarbage()
    {
        $files = $this->fileRepository->findGarbage();
        foreach($files as $file) {
            $this->em->remove($file);
        }
        $this->em->flush();
        return $files;
    }
}