<?php
/**
 * AbstractFixture.php
 *
 * @since 27/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

abstract class AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->loadData($this->getName());
    }

    /**
     * Load the data from fixture file and insert it into the database
     *
     * @param $name
     * @throws \Exception
     */
    public function loadData($name) {

        $file = sprintf('%s/../Resources/fixtures/%s.yml', __DIR__ , $name);
        if(!file_exists($file)) {
            throw new \Exception(sprintf('fixtures file "%s" not found for name "%s"', $file, $name));
        }

        $data = Yaml::parse($file);

        foreach ($data as $args) {
            $item = $this->create($args);
            $this->manager->persist($item);
        }
        $this->manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Save file and return its model.
     *
     * @param $path
     * @return \Enhavo\Bundle\MediaBundle\Model\FileInterface
     * @throws \Exception
     */
    protected function createImage($path)
    {
        $path = sprintf('%s/../Resources/images/%s', __DIR__, $path);
        return $this->container->get('enhavo_media.file_service')->storeFile($path);
    }

    /**
     * Return DateTime object
     *
     * @param $value
     * @return \DateTime
     */
    public function createDateTime($value)
    {
        $date = null;

        if(is_string($value)) {
            $date = new \DateTime($value);
        }

        if(is_int($value)) {
            $date = new \DateTime();
            $date->setTimestamp($value);
        }

        return $date;
    }

    /**
     * Return single Entity by Argument
     *
     * @param $args
     * @return mixed
     */
    abstract function create($args);

    /**
     * Return the name of the fixture
     *
     * @return mixed
     */
    abstract function getName();

    /**
     * {@inheritDoc}
     */
    abstract function getOrder();
}