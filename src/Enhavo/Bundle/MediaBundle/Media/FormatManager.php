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
        return $this->applyFilter($fileFormat, $parameters);
    }

    public function getFormat(FileInterface $file, $format, $parameters = [])
    {
        $fileFormat = $this->formatRepository->findOneBy([
            'name' => $format,
            'file' => $file
        ]);

        if($fileFormat === null) {
            $fileFormat = $this->createFormat($file, $format, $parameters);
        }
        return $fileFormat;
    }

    public function applyFormat(FileInterface $file, $format, $parameters = [])
    {
        /** @var Format $fileFormat */
        $fileFormat = $this->formatRepository->findOneBy([
            'name' => $format,
            'file' => $file
        ]);

        if($fileFormat === null) {
            return $this->createFormat($file, $format, $parameters);
        }
        $this->applyFilter($fileFormat, $parameters);
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

    private function applyFilter(FormatInterface $fileFormat, $parameters)
    {
        $parameters = array_merge($fileFormat->getParameters(), $parameters);
        $settings = $this->getFormatSettings($fileFormat->getName(), $parameters);

        $newFileFormat = $this->formatFactory->createFromMediaFile($fileFormat->getFile());
        $fileFormat->setContent($newFileFormat->getContent());

        foreach($settings as $setting) {
            /** @var FilterInterface $filter */
            $filter = $this->filterCollector->getType($setting->getType());
            $filter->apply($fileFormat, $setting);
        }

        $this->em->persist($fileFormat);
        $this->em->flush();
        $this->storage->saveFile($fileFormat);
        $this->cache->refresh($fileFormat->getFile(), $fileFormat->getName());

        return $fileFormat;
    }

    public function deleteFormats(FileInterface $file)
    {
        $formats = $this->formatRepository->findBy([
            'file' => $file
        ]);
        foreach($formats as $format) {
            $this->deleteFormat($format);
        }
    }

    public function saveFormat(FormatInterface $format)
    {
        $this->em->persist($format);
        $this->em->flush();
        $this->storage->saveFile($format);
    }

    public function deleteFormat(FormatInterface $format)
    {
        $this->em->remove($format);
        $this->em->flush();
        $this->storage->deleteFile($format);
    }

    /**
     * @param string $format format name
     * @param array $parameters
     * @return FilterSetting[]
     * @throws \Exception
     */
    private function getFormatSettings($format, $parameters = [])
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
}
