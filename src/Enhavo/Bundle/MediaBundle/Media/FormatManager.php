<?php
/**
 * FormatManager.php
 *
 * @since 30/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\MediaBundle\Cache\CacheInterface;
use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Exception\FormatException;
use Enhavo\Bundle\MediaBundle\Factory\FormatFactory;
use Enhavo\Bundle\MediaBundle\Filter\FilterInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;
use Enhavo\Bundle\MediaBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MediaBundle\Repository\FormatRepository;
use Enhavo\Bundle\MediaBundle\Storage\StorageInterface;

class FormatManager
{
    const LOCK_TIMEOUT = 180; // 3 minutes

    /**
     * @var array
     */
    private $formats;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var FormatRepository
     */
    private $formatRepository;

    /**
     * @var FormatFactory
     */
    private $formatFactory;

    /**
     * @var TypeCollector
     */
    private $filterCollector;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        $formats,
        StorageInterface $storage,
        FormatRepository $repository,
        FormatFactory $formatFactory,
        TypeCollector $filterCollector,
        EntityManagerInterface $em,
        ProviderInterface $provider,
        CacheInterface $cache
    )
    {
        $this->formats = $formats;
        $this->storage = $storage;
        $this->formatRepository = $repository;
        $this->formatFactory = $formatFactory;
        $this->filterCollector = $filterCollector;
        $this->em = $em;
        $this->provider = $provider;
        $this->cache = $cache;
    }

    private function createFormat(FileInterface $file, $format, $parameters = [])
    {
        $fileFormat = $this->formatFactory->createFromMediaFile($file);
        $fileFormat->setName($format);
        $this->em->persist($fileFormat);
        return $this->applyFilter($fileFormat, $parameters);
    }

    public function getFormat(FileInterface $file, $format, $parameters = [])
    {
        /** @var FormatInterface|null $fileFormat */
        $fileFormat = $this->formatRepository->findByFormatNameAndFile($format, $file);
        $fileFormat = $this->waitForLock($fileFormat);

        if($fileFormat === null) {
            $fileFormat = $this->createFormat($file, $format, $parameters);
        }

        $this->cleanUpTimedOutLockFormats();

        return $fileFormat;
    }

    public function applyFormat(FileInterface $file, $format, $parameters = [])
    {
        if (!$this->existsFormat($format)) {
            throw new FormatException(sprintf('Format "%s" not exists', $format));
        }

        /** @var Format $fileFormat */
        $fileFormat = $this->formatRepository->findByFormatNameAndFile($format, $file);
        $fileFormat = $this->waitForLock($fileFormat);

        if($fileFormat === null) {
            return $this->createFormat($file, $format, $parameters);
        }
        $this->applyFilter($fileFormat, $parameters);

        $this->cleanUpTimedOutLockFormats();

        return $fileFormat;
    }

    public function applyFormats(FileInterface $file)
    {
        /** @var Format[] $fileFormats */
        $fileFormats = $this->formatRepository->findBy([
            'file' => $file
        ]);

        foreach($fileFormats as $fileFormat) {
            $this->applyFormat($file, $fileFormat->getName());
        }
    }

    /**
     * @param $type
     * @return FilterInterface
     */
    public function getFilter($type)
    {
        /** @var FilterInterface $filter */
        $filter = $this->filterCollector->getType($type);
        return $filter;
    }

    private function applyFilter(FormatInterface $fileFormat, $parameters)
    {
        $this->lockFormat($fileFormat);

        $parameters = array_merge($fileFormat->getParameters(), $parameters);
        $settings = $this->getFormatSettings($fileFormat->getName(), $parameters);

        $newFileFormat = $this->formatFactory->createFromMediaFile($fileFormat->getFile());
        $fileFormat->setContent($newFileFormat->getContent());

        foreach($settings as $setting) {
            $filter = $this->getFilter($setting->getType());
            $filter->apply($fileFormat, $setting);
        }

        $this->storage->saveFile($fileFormat);

        $this->unlockFormat($fileFormat);

        $this->cache->refresh($fileFormat->getFile(), $fileFormat->getName());

        return $fileFormat;
    }

    private function waitForLock(?FormatInterface $fileFormat)
    {
        $timeoutThreshold = new \DateTime(self::LOCK_TIMEOUT . ' seconds ago');

        // No format found
        if ($fileFormat === null) {
            return null;
        }

        // No lock, just return format
        if ($fileFormat->getLockAt() === null) {
            return $fileFormat;
        }

        // Lock is timed out, assume error on build
        if ($fileFormat->getLockAt() < $timeoutThreshold) {
            return null;
        }

        // Wait for operation to finish
        while($fileFormat->getLockAt() !== null && $fileFormat->getLockAt() >= $timeoutThreshold) {
            sleep(1);
            $this->em->refresh($fileFormat);
        }

        // Operation finished, return format
        if ($fileFormat->getLockAt() === null) {
            return $fileFormat;
        }

        // Timeout while waiting
        return null;
    }

    private function lockFormat(FormatInterface $fileFormat)
    {
        $fileFormat->setLockAt(new \DateTime());
        $this->em->flush();
    }

    private function unlockFormat(FormatInterface $fileFormat)
    {
        $fileFormat->setLockAt(null);
        $this->em->flush();
    }

    public function deleteFormats(FileInterface $file, bool $flush = true)
    {
        $formats = $this->formatRepository->findBy([
            'file' => $file
        ]);
        foreach($formats as $format) {
            $this->deleteFormat($format, $flush);
        }
    }

    public function saveFormat(FormatInterface $format)
    {
        $this->em->persist($format);
        $this->em->flush();
        $this->storage->saveFile($format);
    }

    public function deleteFormat(FormatInterface $format, bool $flush = true)
    {
        $this->em->remove($format);
        if ($flush) {
            $this->em->flush();
        }
        $this->storage->deleteFile($format);
    }

    public function existsFormat($formatName)
    {
        return in_array($formatName, array_keys($this->formats));
    }

    /**
     * @param string $format format name
     * @param array $parameters
     * @return FilterSetting[]
     * @throws \Exception
     */
    public function getFormatSettings($format, $parameters = [])
    {
        if(!is_array($this->formats) || !isset($this->formats[$format])) {
            throw new \Exception(sprintf('format "%s" not available', $format));
        }

        $settings = [];
        $formatSettings = $this->formats[$format];

        // look for chain
        if(isset($formatSettings[0]) && is_array($formatSettings[0])) {
            foreach($formatSettings as $index => $chainSettings) {
                if(!isset($chainSettings['type'])) {
                    throw new FormatException(sprintf('No filter type was set for format "%s" in chain with index "%s"', $format, $index));
                }
                $formatSetting = new FilterSetting();
                $formatSetting->setType($chainSettings['type']);
                $formatSetting->setSettings(array_merge($chainSettings, $parameters));
                $settings[] = $formatSetting;
            }
        } else {
            // add single filter
            if(!isset($formatSettings['type'])) {
                throw new FormatException(sprintf('No filter type was set for format "%s"', $format));
            }
            $formatSetting = new FilterSetting();
            $formatSetting->setType($formatSettings['type']);
            $formatSetting->setSettings(array_merge($formatSettings, $parameters));
            $settings[] = $formatSetting;
        }

        return $settings;
    }

    private function cleanUpTimedOutLockFormats()
    {
        $timedOutFormats = $this->formatRepository->findByTimedOutLock(self::LOCK_TIMEOUT);
        foreach($timedOutFormats as $format) {
            $this->em->remove($format);
            $this->em->flush();
            // Associated files are NOT deleted, since they might be part of a later format creation that did not timeout
        }
    }
}
