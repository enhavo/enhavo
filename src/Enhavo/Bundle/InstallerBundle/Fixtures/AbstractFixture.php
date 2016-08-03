<?php
/**
 * AbstractFixture.php
 *
 * @since 27/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures;

use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\GridBundle\Entity\Grid;
use Enhavo\Bundle\GridBundle\Entity\Item;
use Enhavo\Bundle\GridBundle\Entity\Picture;
use Enhavo\Bundle\GridBundle\Entity\Text;
use Enhavo\Bundle\GridBundle\Entity\TextPicture;
use Enhavo\Bundle\GridBundle\Item\ItemTypeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
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

        $items = [];
        foreach ($data as $args) {
            $item = $this->create($args);
            $this->manager->persist($item);
            $items[] = $item;
        }

        $this->manager->flush();

        $this->postLoad($items);
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

    /**
     * @param $grid
     * @return Grid
     */
    protected function createGrid($grid)
    {
        $gridEntity = new Grid();
        foreach($grid as $fields) {
            $itemEntity = new Item();
            $type = $fields['type'];
            unset($fields['type']);
            $itemEntity->setItemType($this->createItemType($type, $fields));
            $gridEntity->addItem($itemEntity);
        }
        return $gridEntity;
    }

    /**
     * @param $type
     * @param $fields
     * @return ItemTypeInterface
     */
    protected function createItemType($type, $fields)
    {
        $factory = $this->container->get('enhavo_grid.factory.item_type');
        $itemType = $factory->create($type);
        $this->setFields($type, $itemType, $fields);
        return $itemType;
    }

    protected function setFields($type, $itemType, $fields)
    {
        switch($type) {
            case('text'):
                /** @var $itemType Text */
                $itemType->setText($fields['text']);
                $itemType->setTitle($fields['title']);
                break;
            case('picture'):
                /** @var $itemType Picture */
                $itemType->setFile($this->createImage($fields['file']));
                $itemType->setTitle($fields['title']);
                $itemType->setCaption($fields['caption']);
                break;
            case('text_picture'):
                /** @var $itemType TextPicture */
                $itemType->setFile($this->createImage($fields['file']));
                $itemType->setTitle($fields['title']);
                $itemType->setCaption($fields['caption']);
                $itemType->setFloat($fields['float']);
                $itemType->setTitle($fields['title']);
                $itemType->setText($fields['text']);
                break;
        }
    }

    protected function createRoute($url)
    {
        $route = new Route();
        $route->setStaticPrefix($url);
        return $route;
    }

    protected function postLoad($items)
    {
        $resolver = $this->container->get('enhavo_app.route_content_resolver');

        foreach($items as $item) {
            if($item instanceof Routeable && $item->getRoute()) {
                /** @var Route $route */
                $route = $item->getRoute();
                $route->setType($resolver->getType($item));
                $route->setTypeId($item->getId());
                $route->setName(sprintf('dynamic_route_%s', $route->getId()));
            }
        }

        $this->manager->flush();
    }
}