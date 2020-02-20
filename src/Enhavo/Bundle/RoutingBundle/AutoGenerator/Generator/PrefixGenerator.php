<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrefixGenerator extends AbstractGenerator
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EntityRepositoryInterface
     */
    private $routeRepository;

    public function __construct($em, $routeRepository)
    {
        $this->em = $em;
        $this->routeRepository = $routeRepository;
    }

    public function generate($resource, $options = [])
    {
        $properties = $this->getSlugifiedProperties($resource, $options['properties']);
        if(count($properties)) {
            /** @var RouteInterface $route */
            $route = $this->getProperty($resource, $options['route_property']);
            if(!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix($this->createUrl($properties, $options));
        }
    }

    private function getSlugifiedProperties($resource, $properties)
    {
        $result = [];

        if(!is_array($properties)){
            $properties = [$properties];
        }

        foreach ($properties as $property){
            $slug = $this->getSlug($this->getProperty($resource, $property));
            if($slug){
                $result[$property] = $slug;
            }
        }

        return $result;
    }

    private function getSlug($string)
    {
        return Slugifier::slugify($string);
    }

    private function createUrl(array $properties, array $options)
    {
        return $options['unique'] ? $this->getUniqueUrl($properties, $options) : $this->format($properties, $options);
    }

    private function format(array $properties, array $options)
    {
        if($options['format']){
            return $this->replace($options['format'], $properties);
        } else {
            return sprintf('/%s', join('-', $properties));
        }
    }

    private function replace($string, $properties)
    {
        foreach ($properties as $key => $value){
            $string = str_replace(sprintf('{%s}', $key), $value, $string);
        }

        return $string;
    }

    /**
     * $isFirstTry prevents unintended increase. E.g. if user enters "12-12-12" and entry exists, it should become "12-12-12-1", not "12-12-13".
     */
    private function getUniqueUrl(array $properties, $options, $isFirstTry = true)
    {
        $results = $this->routeRepository->findBy([
            'staticPrefix' => $this->format($properties, $options)
        ]);

        if(count($results)){
            return $this->getUniqueUrl($this->increase($properties, $options, $isFirstTry), $options, false);
        } else {
            return $this->format($properties, $options);
        }
    }

    private function increase($properties, $options, $isFirstTry)
    {
        $string = $properties[$this->getUniqueProperty($properties, $options)];
        $added = false;

        if(!$isFirstTry){
            $isMatch = preg_match('/^(.*)-([0-9]+)$/', $string, $matches);
            if($isMatch && isset($matches[1]) && isset($matches[2])){
                $string = sprintf('%s-%u', $matches[1], intval($matches[2]) + 1);
                $added = true;
            }
        }

        if(!$added){
            $string = sprintf('%s-1', $string);
        }

        $properties[$this->getUniqueProperty($properties, $options)] = $string;

        return $properties;
    }

    private function getUniqueProperty($properties, $options)
    {
        if($options['unique_property']){
            return $options['unique_property'];
        } elseif (count($properties) === 1 && !$options['unique_property']){
            return array_key_first($properties);
        }
        throw new \Exception('Error: "unique" option is set and multiple properties exist. Please specify a "unique_property".');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
            'format' => null,
            'unique' => false,
            'unique_property' => null
        ]);
        $resolver->setRequired([
            'properties'
        ]);
    }

    public function getType()
    {
        return 'prefix';
    }
}
