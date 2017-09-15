<?php
/**
 * MediaManager.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class MediaManager
{
    /**
     * @var FormatManager
     */
    private $formatManager;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var TokenGeneratorInterface;
     */
    private $tokenGenerator;

    /**
     * MediaManager constructor.
     * @param FormatManager $formatManager
     * @param StorageInterface $storage
     * @param ProviderInterface $provider
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(
        FormatManager $formatManager,
        StorageInterface $storage,
        ProviderInterface $provider,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->formatManager = $formatManager;
        $this->storage = $storage;
        $this->provider = $provider;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param array $criteria
     * @return FileInterface
     */
    public function findOneBy($criteria)
    {
        $file = $this->provider->findOneBy($criteria);
        if($file !== null) {
            $this->storage->applyContent($file);
        }
        return $file;
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @return FileInterface[]
     */
    public function findBy(array $criteria = [], array $orderBy = [], $limit = null, $offset = null)
    {
        $files = $this->provider->findBy($criteria, $orderBy, $limit, $offset);
        foreach($files as $file) {
            $this->storage->applyContent($file);
        }
        return $files;
    }

    /**
     * @param int $id
     * @return FileInterface
     */
    public function find($id)
    {
        $file = $this->provider->find($id);
        if($file !== null) {
            $this->storage->applyContent($file);
        }
        return $file;
    }

    /**
     * Deletes file and associated formats.
     *
     * @param FileInterface $file
     */
    public function deleteFile(FileInterface $file)
    {
        $this->provider->delete($file);
        $this->storage->deleteFile($file);
        $this->formatManager->deleteFormats($file);
    }

    /**
     * Save file to system.
     *
     * @param FileInterface $file
     */
    public function saveFile(FileInterface $file)
    {
        $content = $file->getContent()->getContent();
        $file->setMd5Checksum(md5($content));

        if($file->getToken() === null) {
            $file->setToken($this->generateToken());
        }

        $this->provider->save($file);
        $this->storage->saveFile($file);
    }

    /**
     * @param FileInterface $file
     * @param string $format
     *
     * @return FormatInterface
     */
    public function getFormat(FileInterface $file, $format)
    {
        return $this->formatManager->getFormat($file, $format);
    }

    public function saveFormat(FormatInterface $format)
    {
        $this->formatManager->saveFormat($format);
    }

    public function deleteFormat(Format $format)
    {
        $this->formatManager->deleteFormat($format);
    }

    public function collectGarbage()
    {
        $files = $this->provider->collectGarbage();
        foreach($files as $file) {
            $this->storage->deleteFile($file);
            $this->formatManager->deleteFormats($file);
        }
    }

    private function generateToken()
    {
        do {
            $token = $this->tokenGenerator->generateToken(10);
            $file = $this->provider->findOneBy([
                'token' => $token
            ]);
        } while($file !== null); //make sure token is unique
        return $token;
    }
}